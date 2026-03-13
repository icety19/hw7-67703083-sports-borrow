<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require_once 'config/db.php';

$database = new Database();
$db = $database->getConnection();

// Fetch Equipment
$query = "SELECT * FROM equipment ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$equipment = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Stats for dashboard
$total = count($equipment);
$available = 0;
foreach($equipment as $item) if($item['available_quantity'] > 0) $available++;
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise Dashboard | Sports Borrow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', 'Prompt', sans-serif; background-color: #f8fafc; }
        .sidebar { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }
        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            border-color: #3b82f6;
        }
        .nav-link {
            transition: all 0.2s ease;
        }
        .nav-link:hover {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }
        .nav-link.active {
            background: #3b82f6;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.4);
        }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>
</head>
<body class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="sidebar w-64 bg-white border-r border-slate-200 hidden lg:flex flex-col flex-shrink-0">
        <div class="p-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-600/30">
                    <i class="fas fa-volleyball-ball text-lg"></i>
                </div>
                <h1 class="text-xl font-bold text-slate-800 tracking-tight">Sports Central</h1>
            </div>
        </div>

        <nav class="flex-1 px-4 py-4 space-y-2 custom-scrollbar overflow-y-auto">
            <a href="dashboard.php" class="nav-link active flex items-center gap-3 px-4 py-3 rounded-xl font-semibold">
                <i class="fas fa-th-large w-5"></i> Dashboard
            </a>
            <a href="#" class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 font-medium">
                <i class="fas fa-exchange-alt w-5"></i> Borrow / Return
            </a>
            <a href="#" class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 font-medium">
                <i class="fas fa-history w-5"></i> History
            </a>
            <div class="pt-4 pb-2 px-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Management</p>
            </div>
            <a href="#" class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 font-medium">
                <i class="fas fa-users w-5"></i> Members
            </a>
            <a href="#" class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl text-slate-500 font-medium">
                <i class="fas fa-cog w-5"></i> System Settings
            </a>
        </nav>

        <div class="p-4 border-t border-slate-100">
            <div class="bg-slate-50 rounded-2xl p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold border-2 border-white shadow-sm">
                    <?php echo strtoupper(substr($_SESSION['full_name'], 0, 1)); ?>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-800 truncate"><?php echo $_SESSION['full_name']; ?></p>
                    <p class="text-[10px] font-medium text-slate-500 uppercase"><?php echo $_SESSION['role']; ?></p>
                </div>
                <a href="logout.php" class="text-slate-400 hover:text-red-500 transition-colors">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-w-0 bg-slate-50 overflow-hidden">
        <!-- Header -->
        <header class="bg-white/80 backdrop-blur-md border-b border-slate-200 px-8 py-4 flex justify-between items-center sticky top-0 z-10">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Equipment Catalog</h2>
                <p class="text-xs text-slate-500">Manage and track your sports inventory</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="relative hidden md:block">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" placeholder="Search gear..." class="bg-slate-100 border-none rounded-full pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 w-64 transition-all">
                </div>
                <button onclick="addEquipment()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold flex items-center gap-2 shadow-lg shadow-blue-600/20 transition-all active:scale-95">
                    <i class="fas fa-plus"></i> <span class="hidden sm:inline">Add Gear</span>
                </button>
            </div>
        </header>

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 flex items-center gap-5">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                        <i class="fas fa-boxes-stacked text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Total Items</p>
                        <h4 class="text-3xl font-extrabold text-slate-800"><?php echo $total; ?></h4>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 flex items-center gap-5">
                    <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center text-green-600">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Available</p>
                        <h4 class="text-3xl font-extrabold text-slate-800"><?php echo $available; ?></h4>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 flex items-center gap-5">
                    <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">In Use</p>
                        <h4 class="text-3xl font-extrabold text-slate-800"><?php echo $total - $available; ?></h4>
                    </div>
                </div>
            </div>

            <!-- Grid Header -->
            <div class="flex justify-between items-end mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Inventory Status</h3>
                    <p class="text-sm text-slate-500">Live view of all sports equipment</p>
                </div>
                <div class="flex gap-2">
                    <div class="bg-white border border-slate-200 rounded-lg p-1 flex">
                        <button class="px-3 py-1 bg-slate-100 rounded text-slate-800 font-semibold text-xs"><i class="fas fa-th-large"></i></button>
                        <button class="px-3 py-1 text-slate-400 hover:text-slate-600 text-xs"><i class="fas fa-list"></i></button>
                    </div>
                </div>
            </div>

            <!-- Equipment Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-8">
                <?php foreach($equipment as $item): ?>
                <div class="glass-card rounded-[2rem] p-5 flex flex-col group">
                    <!-- Image Placeholder/Preview -->
                    <div class="relative h-44 bg-slate-100 rounded-[1.5rem] mb-5 overflow-hidden flex items-center justify-center group-hover:shadow-inner transition-all">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <?php if($item['image_url']): ?>
                            <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="flex flex-col items-center gap-2 opacity-30 group-hover:opacity-60 transition-opacity">
                                <i class="fas fa-image text-5xl"></i>
                                <span class="text-[10px] uppercase font-bold tracking-widest">No Image</span>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1.5 backdrop-blur-md bg-white/90 text-slate-800 text-[10px] font-extrabold uppercase rounded-full shadow-sm border border-white/50">
                                <?php echo $item['category']; ?>
                            </span>
                        </div>
                    </div>

                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-slate-800 line-clamp-1 group-hover:text-blue-600 transition-colors"><?php echo $item['name']; ?></h3>
                        </div>
                        
                        <div class="flex items-center gap-4 mb-5">
                            <div class="flex-1 bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-blue-600 h-full rounded-full" style="width: <?php echo ($item['available_quantity'] / $item['total_quantity']) * 100; ?>%"></div>
                            </div>
                            <span class="text-xs font-bold text-slate-500">
                                <span class="text-slate-900"><?php echo $item['available_quantity']; ?></span>/<?php echo $item['total_quantity']; ?>
                            </span>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button onclick="editEquipment(<?php echo htmlspecialchars(json_encode($item)); ?>)" class="flex-1 py-3 bg-white border border-slate-200 hover:border-blue-500 hover:text-blue-600 text-slate-600 rounded-2xl text-xs font-bold transition-all active:scale-95 shadow-sm">
                            Edit Detail
                        </button>
                        <button onclick="deleteEquipment(<?php echo $item['id']; ?>)" class="w-12 h-11 bg-red-50 hover:bg-red-100 text-red-500 rounded-2xl flex items-center justify-center transition-all active:scale-95">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
    <script>
        function addEquipment() {
            $.confirm({
                title: '<span class="text-blue-600 font-bold">Register New Equipment</span>',
                content: '' +
                '<form action="" class="p-2">' +
                '<div class="mb-4">' +
                '<label class="text-xs font-bold text-slate-500 uppercase tracking-widest pl-1">Item Name</label>' +
                '<input type="text" placeholder="e.g. Wilson Basketball" class="item_name w-full mt-2 p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all" required />' +
                '</div>' +
                '<div class="mb-4">' +
                '<label class="text-xs font-bold text-slate-500 uppercase tracking-widest pl-1">Category</label>' +
                '<select class="item_category w-full mt-2 p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">' +
                '<option value="Ball">Ball</option>' +
                '<option value="Racket">Racket</option>' +
                '<option value="Net">Net</option>' +
                '<option value="Accessory">Accessory</option>' +
                '</select>' +
                '</div>' +
                '<div class="mb-2">' +
                '<label class="text-xs font-bold text-slate-500 uppercase tracking-widest pl-1">Stock Quantity</label>' +
                '<input type="number" value="1" min="1" class="item_qty w-full mt-2 p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all" required />' +
                '</div>' +
                '</form>',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'blue',
                buttons: {
                    confirm: {
                        text: 'Register Item',
                        btnClass: 'btn-blue',
                        action: function () {
                            var name = this.$content.find('.item_name').val();
                            var category = this.$content.find('.item_category').val();
                            var qty = this.$content.find('.item_qty').val();
                            if(!name){
                                return false;
                            }
                            $.post('handle_equipment.php', {
                                action: 'add',
                                name: name,
                                category: category,
                                quantity: qty
                            }, function(res) {
                                location.reload();
                            });
                        }
                    },
                    cancel: { text: 'Discard' }
                }
            });
        }

        function deleteEquipment(id) {
            $.confirm({
                title: '<span class="text-red-600 font-bold">Remove Equipment?</span>',
                content: 'This action cannot be undone. All related data will be permanently deleted.',
                type: 'red',
                theme: 'modern',
                icon: 'fas fa-exclamation-triangle',
                buttons: {
                    confirm: {
                        text: 'Yes, Delete',
                        btnClass: 'btn-red',
                        action: function() {
                            $.post('handle_equipment.php', {
                                action: 'delete',
                                id: id
                            }, function(res) {
                                location.reload();
                            });
                        }
                    },
                    cancel: { text: 'Cancel' }
                }
            });
        }

        function editEquipment(item) {
            $.confirm({
                title: '<span class="text-blue-600 font-bold">Edit Equipment Details</span>',
                content: '' +
                '<form action="" class="p-2">' +
                '<div class="mb-4">' +
                '<label class="text-xs font-bold text-slate-500 uppercase tracking-widest pl-1">Item Name</label>' +
                '<input type="text" value="' + item.name + '" class="item_name w-full mt-2 p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all" required />' +
                '</div>' +
                '<div class="mb-4">' +
                '<label class="text-xs font-bold text-slate-500 uppercase tracking-widest pl-1">Category</label>' +
                '<select class="item_category w-full mt-2 p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">' +
                '<option value="Ball" ' + (item.category == 'Ball' ? 'selected' : '') + '>Ball</option>' +
                '<option value="Racket" ' + (item.category == 'Racket' ? 'selected' : '') + '>Racket</option>' +
                '<option value="Net" ' + (item.category == 'Net' ? 'selected' : '') + '>Net</option>' +
                '<option value="Accessory" ' + (item.category == 'Accessory' ? 'selected' : '') + '>Accessory</option>' +
                '</select>' +
                '</div>' +
                '<div class="mb-2">' +
                '<label class="text-xs font-bold text-slate-500 uppercase tracking-widest pl-1">Stock Quantity</label>' +
                '<input type="number" value="' + item.total_quantity + '" min="1" class="item_qty w-full mt-2 p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all" required />' +
                '</div>' +
                '</form>',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'blue',
                buttons: {
                    confirm: {
                        text: 'Save Changes',
                        btnClass: 'btn-blue',
                        action: function () {
                            var name = this.$content.find('.item_name').val();
                            var category = this.$content.find('.item_category').val();
                            var qty = this.$content.find('.item_qty').val();
                            if(!name){
                                return false;
                            }
                            $.post('handle_equipment.php', {
                                action: 'update',
                                id: item.id,
                                name: name,
                                category: category,
                                quantity: qty
                            }, function(res) {
                                location.reload();
                            });
                        }
                    },
                    cancel: { text: 'Cancel' }
                }
            });
        }
    </script>
</body>
</html>
