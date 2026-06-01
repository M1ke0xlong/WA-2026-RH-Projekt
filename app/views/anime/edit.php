<?php require_once '../app/views/layout/header.php'; ?>

<main class="max-w-3xl mx-auto px-6 py-12 w-full flex-1">
    
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-slate-800">Upravit Anime</h2>
        
        <a href="<?= BASE_URL ?>/index.php?url=admin/dashboard" class="text-slate-500 hover:text-sakura transition-colors text-sm font-medium">&larr; Zpět na panel</a>
    </div> 

    <div class="bg-white p-8 rounded-xl shadow-sm border border-sakura-light">
        <form action="<?= BASE_URL ?>/index.php?url=anime/edit/<?= $anime['id'] ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            
            <input type="hidden" name="current_image" value="<?= htmlspecialchars($anime['image'] ?? '') ?>">

            <div class="border-b border-slate-100 pb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Hlavní název (Povinné)</label>
                <input type="text" name="primary_title" required 
                       value="<?= htmlspecialchars($anime['primary_title'] ?? '') ?>"
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sakura focus:border-transparent transition-shadow">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 border-b border-slate-100 pb-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Anglický název</label>
                    <input type="text" name="title_english" value="<?= htmlspecialchars($titles['english'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sakura focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Japonský originál</label>
                    <input type="text" name="title_japanese" value="<?= htmlspecialchars($titles['japanese'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sakura focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Romaji</label>
                    <input type="text" name="title_romaji" value="<?= htmlspecialchars($titles['romaji'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sakura focus:border-transparent">
                </div>
            </div>

            <div class="border-b border-slate-100 pb-6 flex flex-col sm:flex-row gap-6 items-start">
                
                <?php if(!empty($anime['image'])): ?>
                    <div class="w-24 shrink-0">
                        <p class="text-xs text-slate-500 font-bold mb-2">Aktuální:</p>
                        <img src="<?= BASE_URL ?>/uploads/covers/<?= htmlspecialchars($anime['image']) ?>" class="w-full rounded shadow-sm">
                    </div>
                <?php endif; ?>
                
                <div class="grow w-full">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nová obálka (volitelné)</label>
                    <input type="file" name="cover_image" accept="image/jpeg, image/png, image/webp"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sakura file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-sakura-light file:text-sakura-dark hover:file:bg-sakura hover:file:text-white transition-all cursor-pointer text-slate-500">
                    <p class="text-xs text-slate-400 mt-2">Pokud nevybereš nový soubor, zůstane v databázi ten původní.</p>
                </div>
            </div>
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                <label class="block text-sm font-bold text-slate-700 mb-3">Galerie obrázků</label>
                
                <?php if (!empty($gallery)): ?>
                    <div class="mb-4">
                        <p class="text-xs font-bold text-slate-500 mb-2">Aktuální obrázky v galerii:</p>
                        <div class="flex flex-wrap gap-3">
                            <?php foreach ($gallery as $img): ?>
                                <img src="<?= BASE_URL ?>/uploads/gallery/<?= htmlspecialchars($img) ?>" alt="Gallery Image" class="w-20 h-20 object-cover rounded-lg shadow-sm border border-slate-300">
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-xs text-slate-500 mb-4 italic">Zatím nebyly nahrány žádné obrázky do galerie.</p>
                <?php endif; ?>

                <div>
                    <label class="text-xs font-bold text-slate-700 mb-1 block">Přidat další obrázky:</label>
                    <input type="file" name="gallery[]" multiple accept="image/jpeg, image/png, image/webp" 
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sakura file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-sakura-light file:text-sakura-dark hover:file:bg-sakura hover:file:text-white transition-all cursor-pointer text-slate-500">
                    <p class="text-[11px] text-slate-400 mt-1.5 flex items-center gap-1">
                        ➕ <span class="font-medium">Můžeš vybrat více souborů najednou. Nové fotky se přidají k těm stávajícím.</span>
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="sm:col-span-1">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Počet epizod</label>
                    <input type="number" min="1" name="episodes" value="<?= htmlspecialchars($anime['episodes'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sakura focus:border-transparent">
                </div>
                <div class="sm:col-span-3">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Popis (Synopse)</label>
                    <textarea name="description" rows="5" 
                              class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sakura focus:border-transparent resize-none"><?= htmlspecialchars($anime['description'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="pt-4 text-right">
                <button type="submit" class="px-8 py-3 bg-sakura text-white font-bold rounded-lg shadow-sm hover:bg-sakura-dark transition-all hover:-translate-y-0.5">
                    Uložit změny
                </button>
            </div>

        </form>
    </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>