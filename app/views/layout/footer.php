<footer class="bg-white border-t border-sakura-light py-8 mt-auto shadow-[0_-2px_10px_rgba(0,0,0,0.02)]">
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row justify-center items-center gap-4">
            
            <div class="text-slate-500 text-sm text-center md:text-left">
                &copy; <?= date('Y') ?> <span class="font-bold text-branch"><span class="text-sakura">Dere</span>-list</span>. 
                <br class="block sm:hidden">Vytvořeno s láskou k anime (a k semestrálním projektům).
            </div>
        </div>
        
        <div class="text-center mt-6 text-sakura text-opacity-30 text-xl select-none">
            🌸
        </div>
    </footer>
<?php if (isset($_SESSION['toast'])): ?>
        <?php 
            $toast = $_SESSION['toast']; 
            unset($_SESSION['toast']); // Smažeme, aby se ukázal jen jednou
            
            // Barvy podle typu
            $bgColor = 'bg-slate-800'; // default / info
            $icon = 'ℹ️';
            
            if ($toast['type'] === 'success') {
                $bgColor = 'bg-green-600';
                $icon = '✅';
            } elseif ($toast['type'] === 'error') {
                $bgColor = 'bg-red-500';
                $icon = '❌';
            }
        ?>
        
        <div id="toast-message" class="fixed bottom-6 right-6 flex items-center gap-3 px-5 py-3 <?= $bgColor ?> text-white text-sm font-bold rounded-xl shadow-2xl z-50 transform transition-all duration-500 translate-y-0 opacity-100">
            <span><?= $icon ?></span>
            <span><?= htmlspecialchars($toast['message']) ?></span>
        </div>

        <script>
            // JavaScript pro automatické schování toastu po 3 vteřinách
            setTimeout(() => {
                const toast = document.getElementById('toast-message');
                if (toast) {
                    toast.classList.remove('translate-y-0', 'opacity-100');
                    toast.classList.add('translate-y-10', 'opacity-0');
                    // Po dokončení animace prvek úplně odstraníme
                    setTimeout(() => toast.remove(), 500);
                }
            }, 3000);
        </script>
    <?php endif; ?>

</body>
</html>