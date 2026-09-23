        </div>
    </main>
</div>
</div>
<script src="<?= baseUrl('admin/assets/js/app.js') ?>"></script>
<script>
setTimeout(() => {
    document.querySelectorAll('.alert-success, .alert-danger').forEach(el => {
        el.style.transition = 'opacity .5s';
        el.style.opacity = 0;
        setTimeout(() => el.remove(), 500);
    });
}, 4000);
</script>
</body>
</html>