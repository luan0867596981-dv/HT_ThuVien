  </div>
</div>

    <!-- 100% Safe Interaction - Global AI Assistant -->
    <?php if(isset($_SESSION['user'])): ?>
        <?php include 'app/views/layout/chatbot_widget.php'; ?>
    <?php endif; ?>

    <!-- FAILSAFE JS RECOVERY -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <footer class="mt-auto py-4 bg-white border-top border-slate-50">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted small fw-bold uppercase tracking-widest" style="font-size: 0.65rem; letter-spacing: 0.05em;">&copy; 2024 - 2025 LibSaaS Enterprise. All rights reserved.</span>
                <div class="d-flex gap-3">
                    <a href="#" class="text-muted small fw-bold text-decoration-none hover-lift">System Status</a>
                    <a href="#" class="text-muted small fw-bold text-decoration-none hover-lift">Help Center</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
