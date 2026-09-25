        </div><!-- /.admin-content -->
    </div><!-- /.admin-main -->
</div><!-- /.admin-layout -->

<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
<script>
    // Live clock in admin topbar
    function updateClock() {
        const el = document.getElementById('topbarTime');
        if (el) el.textContent = new Date().toLocaleTimeString('sw-TZ', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }
    updateClock();
    setInterval(updateClock, 1000);

    // Sidebar toggle for mobile
    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
        document.getElementById('adminSidebar').classList.toggle('open');
    });
    document.getElementById('sidebarClose')?.addEventListener('click', () => {
        document.getElementById('adminSidebar').classList.remove('open');
    });
</script>
<?= $extraScripts ?? '' ?>
</body>
</html>
