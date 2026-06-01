<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dere-list</title>
    
    <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>/images/logo_top.svg">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sakura: {
                            light: '#fce7f3', 
                            DEFAULT: '#f472b6', 
                            dark: '#db2777',    
                        },
                        branch: {
                            DEFAULT: '#451a03', // Tmavě hnědá z větvičky pro texty
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#fafafa] text-branch font-sans antialiased min-h-screen grid grid-rows-[auto_1fr_auto]">
    
    <header class="bg-white border-b border-sakura-light py-4 px-6 shadow-sm sticky top-0 z-50">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            
            <h1 class="text-2xl font-bold flex items-center gap-1.5">
                <a href="<?= BASE_URL ?>" class="flex items-center gap-1.5 hover:opacity-80 transition-opacity">
                    <img src="<?= BASE_URL ?>/images/logo.svg" alt="Dere-list Logo" class="w-8 h-8 object-contain">
                    <span class="text-branch"><span class="text-sakura">Dere</span>-list</span>
                </a>
            </h1>
            
            <nav class="flex items-center gap-4 sm:gap-6">
                <a href="<?= BASE_URL ?>" class="text-slate-500 hover:text-sakura transition-colors font-medium">Domů</a>

                <?php if (isset($_SESSION['user_id'])): ?>
                    
                    <div class="flex items-center gap-4 border-l border-slate-200 pl-4 sm:pl-6">
                        
                        <a href="<?= BASE_URL ?>/index.php?url=anime/create" class="hidden sm:inline-flex items-center gap-1.5 text-sm bg-sakura text-white px-3 py-1.5 rounded-lg font-bold shadow-sm hover:bg-sakura-dark transition-all">
                            + Přidat anime
                        </a>

                        <div class="flex items-center gap-2">
                            <a href="<?= BASE_URL ?>/index.php?url=user/profile" class="text-sm font-bold text-slate-800 hover:text-sakura transition-colors">
                                <?= htmlspecialchars($_SESSION['username'] ?? '') ?>
                            </a>
                            
                            <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
                                <a href="<?= BASE_URL ?>/index.php?url=admin/dashboard" class="bg-red-500 hover:bg-red-600 transition-colors text-white text-[10px] uppercase tracking-widest font-black px-1.5 py-0.5 rounded shadow-sm">
                                    Admin Panel
                                </a>
                            <?php endif; ?>
                        </div>

                        <a href="<?= BASE_URL ?>/index.php?url=auth/logout" class="text-sm text-slate-400 hover:text-red-500 transition-colors font-medium">Odhlásit</a>
                    </div>

                <?php else: ?>

                    <div class="flex items-center gap-4 border-l border-slate-200 pl-4 sm:pl-6">
                        <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="text-slate-500 hover:text-sakura transition-colors font-medium text-sm">
                            Přihlásit
                        </a>
                        <a href="<?= BASE_URL ?>/index.php?url=auth/register" class="bg-sakura text-white px-4 py-2 rounded-lg font-bold text-sm shadow-sm hover:bg-sakura-dark transition-all">
                            Registrace
                        </a>
                    </div>

                <?php endif; ?>
                
            </nav>
        </div>
    </header>