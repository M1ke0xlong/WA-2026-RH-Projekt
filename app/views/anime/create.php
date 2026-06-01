<?php require_once '../app/views/layout/header.php'; ?>

<main class="max-w-3xl mx-auto px-6 py-12 w-full flex-1">
    
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-slate-800">Přidat nové Anime</h2>
        <a href="<?= BASE_URL ?>/index.php" class="text-slate-500 hover:text-sakura transition-colors text-sm font-medium">&larr; Zpět na seznam</a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-6">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?> 

    <div class="bg-white p-8 rounded-xl shadow-sm border border-sakura-light">
        <form action="<?= BASE_URL ?>/index.php?url=anime/create" method="POST" enctype="multipart/form-data" class="space-y-6">
            
            <div class="border-b border-slate-100 pb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Hlavní název (Povinné)</label>
                <input type="text" name="primary_title" required 
                       value="<?= htmlspecialchars($data['primary_title'] ?? '') ?>"
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sakura focus:border-transparent transition-shadow" 
                       placeholder="Např. Shingeki no Kyojin">
            </div>
            <div class="border-b border-slate-100 pb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Obálka anime (Povinné, Cover Image 3:4)</label>
                <input type="file" name="cover_image" accept="image/jpeg, image/png, image/webp" required
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sakura file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-sakura-light file:text-sakura-dark hover:file:bg-sakura hover:file:text-white transition-all cursor-pointer text-slate-500">
                <p class="text-xs text-slate-400 mt-2">Doporučený formát: JPG, PNG nebo WEBP. Poměr stran 3:4.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 border-b border-slate-100 pb-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Anglický název</label>
                    <input type="text" name="title_english" value="<?= htmlspecialchars($data['title_english'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sakura focus:border-transparent" 
                           placeholder="Attack on Titan">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Japonský originál</label>
                    <input type="text" name="title_japanese" value="<?= htmlspecialchars($data['title_japanese'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sakura focus:border-transparent" 
                           placeholder="進撃の巨人">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Romaji</label>
                    <input type="text" name="title_romaji" value="<?= htmlspecialchars($data['title_romaji'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sakura focus:border-transparent" 
                           placeholder="Shingeki no Kyojin">
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="sm:col-span-1">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Počet epizod (Povinné)</label>
                    <input  type="number" min="1" name="episodes" value="<?= htmlspecialchars($data['episodes'] ?? '' ) ?> required"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sakura focus:border-transparent" 
                           placeholder="24">
                </div>
                <div class="sm:col-span-3">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Popis (Synopse)</label>
                    <textarea name="description" rows="4" 
                              class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sakura focus:border-transparent resize-none" 
                              placeholder="O čem to je?"><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
                </div>
            </div>
            <div class="border-b border-slate-100 pb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Galerie obrázků (Screenshoty z epizod)
                </label>
                <div class="relative bg-slate-50 p-4 rounded-xl border border-dashed border-slate-300 hover:border-sakura transition-colors">
                    <input type="file" name="gallery[]" multiple accept="image/jpeg, image/png, image/webp"
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-sakura-light file:text-sakura-dark hover:file:bg-sakura hover:file:text-white transition-all cursor-pointer">
                    <p class="text-xs text-slate-400 mt-2 flex items-center gap-1">
                        🌸 <span>Při výběru souborů podrž klávesu <strong>Ctrl</strong> (nebo Cmd na Macu) pro označení více obrázků najednou.</span>
                    </p>
                </div>
            </div>
            <div class="pt-4 text-right">
                <button type="submit" class="px-8 py-3 bg-sakura text-white font-bold rounded-lg shadow-sm hover:bg-sakura-dark transition-all hover:-translate-y-0.5">
                    Přidat do databáze
                </button>
            </div>

        </form>
    </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>