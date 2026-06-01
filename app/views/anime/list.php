<?php require_once '../app/views/layout/header.php'; ?>
    <main class="max-w-6xl mx-auto px-6 py-12 w-full">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4">
        <h2 class="text-3xl font-bold text-slate-800 shrink-0">Seznam Anime</h2>
        
        <form action="<?= BASE_URL ?>/index.php" method="GET" class="w-full sm:w-auto relative">
            <input type="text" name="q" placeholder="Hledat anime..." 
                   value="<?= htmlspecialchars($searchQuery ?? '') ?>"
                   class="w-full sm:w-80 pl-10 pr-4 py-2 border border-slate-300 rounded-full focus:outline-none focus:ring-2 focus:ring-sakura focus:border-transparent transition-shadow text-sm">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                🔍
            </span>
            <?php if (!empty($searchQuery)): ?>
                <a href="<?= BASE_URL ?>/index.php" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-500 text-xs font-bold transition-colors">
                    ✕
                </a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (empty($animes) && !empty($searchQuery)): ?>
        <div class="bg-slate-50 p-8 rounded-xl border border-slate-200 text-center text-slate-500 mb-8">
            Nenašli jsme žádné anime odpovídající výrazu <strong>"<?= htmlspecialchars($searchQuery) ?>"</strong>.
        </div>
    <?php endif; ?>

    <?php if (empty($animes)): ?>
        <div class="bg-white p-8 rounded-xl shadow-sm border border-sakura-light text-center text-slate-500">
            Zatím tu není žádné anime. Buď první, kdo nějaké přidá!
        </div>
    <?php else: ?>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            
            <?php foreach ($animes as $anime): ?>
                <div class="group relative bg-white rounded-xl shadow-sm border border-sakura-light overflow-hidden transition-all duration-300 hover:-translate-y-1.5 hover:shadow-lg flex flex-col">
                    
                    <a href="<?= BASE_URL ?>/index.php?url=anime/detail/<?= htmlspecialchars($anime['id']) ?>" class="block relative aspect-[3/4] bg-slate-100 overflow-hidden shrink-0">
                        <span class="absolute top-2 right-2 z-10 bg-slate-900/80 backdrop-blur-sm text-white text-[10px] uppercase tracking-wider font-bold px-2 py-1 rounded shadow-sm">
                            <?= htmlspecialchars($anime['episodes'] ?? '?') ?> ep
                        </span>

                        <?php if (!empty($anime['avg_score'])): ?>
                            <span class="absolute top-2 left-2 z-10 bg-amber-500 text-white text-[11px] font-black px-2 py-1 rounded shadow-sm flex items-center gap-1">
                                ⭐ <?= number_format($anime['avg_score'], 1) ?>
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($anime['image'])): ?>
                            <img src="<?= BASE_URL ?>/uploads/covers/<?= htmlspecialchars($anime['image']) ?>" 
                                 alt="<?= htmlspecialchars($anime['primary_title']) ?>" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <?php else: ?>
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 p-4 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <span class="text-xs font-medium">Bez obrázku</span>
                            </div>
                        <?php endif; ?>

                        <span class="absolute top-2 right-2 bg-slate-900/80 backdrop-blur-sm text-white text-[10px] uppercase tracking-wider font-bold px-2 py-1 rounded shadow-sm">
                            <?= htmlspecialchars($anime['episodes'] ?? '?') ?> ep
                        </span>
                    </a>

                    <div class="p-3 grow flex flex-col items-start">
                        <h3 class="font-bold text-sm leading-tight text-slate-800 group-hover:text-sakura transition-colors line-clamp-2" title="<?= htmlspecialchars($anime['primary_title']) ?>">
                            <a href="<?= BASE_URL ?>/index.php?url=anime/detail/<?= htmlspecialchars($anime['id']) ?>">
                                <?= htmlspecialchars($anime['primary_title']) ?>
                            </a>
                        </h3>
                        
                        <?php if (!empty($anime['english_title'])): ?>
                            <p class="text-[11px] text-slate-500 mt-1 line-clamp-1 font-medium" title="<?= htmlspecialchars($anime['english_title']) ?>">
                                <?= htmlspecialchars($anime['english_title']) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
        </div>
    <?php endif; ?>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>