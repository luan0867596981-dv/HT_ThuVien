<style>
    .ai-search-container {
        min-height: 70vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 2rem;
    }
    .ai-search-box {
        width: 100%;
        max-width: 700px;
        position: relative;
        margin-bottom: 3rem;
        transition: transform 0.3s ease;
    }
    .ai-search-box:focus-within {
        transform: translateY(-5px);
    }
    .ai-input-premium {
        height: 64px;
        border-radius: 32px;
        border: 2px solid #e2e8f0;
        padding: 0 3rem 0 4rem;
        font-size: 1.25rem;
        font-weight: 600;
        letter-spacing: -0.02em;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .ai-input-premium:focus {
        border-color: #6366f1;
        box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.1);
        outline: none;
    }
    .ai-search-icon {
        position: absolute;
        left: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 1.5rem;
    }
    .ai-spinner {
        position: absolute;
        right: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        display: none;
    }
    /* Results Grid */
    #ai-results {
        width: 100%;
        max-width: 900px;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    .card-ai-result {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.25rem;
        transition: all 0.2s ease;
        animation: fadeIn 0.4s ease forwards;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .card-ai-result:hover {
        border-color: #6366f1;
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .badge-shelf-ai {
        background: #f1f5f9;
        color: #4f46e5;
        font-weight: 800;
        font-size: 0.75rem;
        text-transform: uppercase;
        padding: 6px 12px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: auto;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="ai-search-container">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-800 text-slate-900" style="letter-spacing: -0.06em;">LibSaaS <span class="text-indigo-600">AI</span> Search</h1>
        <p class="text-slate-500 fw-600">Smart Asset Discovery powered by Semantic NLP</p>
    </div>

    <div class="ai-search-box">
        <i class="fa-solid fa-wand-magic-sparkles ai-search-icon"></i>
        <input type="text" id="ai-search-input" class="form-control ai-input-premium" placeholder="Bạn muốn tìm sách gì hôm nay? (Ví dụ: Đắc nhân tâm,...)" autocomplete="off">
        <div class="ai-spinner spinner-border spinner-border-sm text-indigo-600" role="status"></div>
    </div>

    <!-- Results Area -->
    <div id="ai-results">
        <!-- AI Results will be injected here via AJAX -->
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const aiInput = document.getElementById('ai-search-input');
    const resultsContainer = document.getElementById('ai-results');
    const spinner = document.querySelector('.ai-spinner');
    let debounceTimer;

    aiInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const keyword = this.value.trim();
        
        if (keyword.length < 2) {
            resultsContainer.innerHTML = '';
            return;
        }

        spinner.style.display = 'block';

        debounceTimer = setTimeout(() => {
            fetch(`index.php?controller=sach&action=live_search_ai&keyword=${encodeURIComponent(keyword)}`)
                .then(response => response.text())
                .then(html => {
                    resultsContainer.innerHTML = html;
                    spinner.style.display = 'none';
                });
        }, 500);
    });
});
</script>
