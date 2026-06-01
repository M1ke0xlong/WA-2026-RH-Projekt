<?php require_once '../app/views/layout/header.php'; ?>

<main class="max-w-md mx-auto px-6 py-12 w-full flex-1">
    
    <div class="bg-white p-8 rounded-xl shadow-sm border border-sakura-light">
        <h2 class="text-2xl font-bold text-center text-slate-800 mb-6">Registrace</h2>

        <?php if (!empty($error)): ?>
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-6 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/index.php?url=auth/register" method="POST" class="space-y-4">
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Uživatelské jméno</label>
                <input type="text" name="username" required 
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sakura focus:border-transparent outline-none">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">E-mail</label>
                <input type="email" name="email" required 
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sakura focus:border-transparent outline-none">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Heslo</label>
                <input type="password" name="password" required 
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sakura focus:border-transparent">
                <p class="text-[11px] text-slate-400 mt-1.5 flex items-center gap-1">
                    🔒 <span class="font-medium">Minimálně 8 znaků, 1 velké písmeno, 1 malé písmeno a 1 číslice.</span>
                </p>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Potvrzení hesla</label>
                <input type="password" name="password_confirm" required 
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sakura focus:border-transparent">
            </div>

            <button type="submit" class="w-full py-3 mt-4 bg-sakura text-white font-bold rounded-lg shadow-sm hover:bg-sakura-dark transition-all">
                Vytvořit účet
            </button>
            
        </form>
        
        <div class="mt-6 text-center text-sm text-slate-500">
            Už máš účet? <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="text-sakura font-bold hover:underline">Přihlas se</a>
        </div>
    </div>

</main>

<?php require_once '../app/views/layout/footer.php'; ?>