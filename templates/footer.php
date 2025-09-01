<?php
// templates/footer.php
$flash = null;
if (session_status() !== PHP_SESSION_NONE && !empty($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}
?>
</main> <!-- /.container -->

<footer class="site-footer">
  <div class="footer-inner">
    <small>© <?= date('Y') ?> Pet Clinic</small>
  </div>
</footer>

<!-- JS -->
<script src="assets/script.js"></script>

<?php if ($flash): ?>
<script>
  document.addEventListener('DOMContentLoaded', function(){
    Swal.fire({
      icon: <?= json_encode($flash['type']) ?>,
      title: <?= json_encode($flash['msg']) ?>,
      showConfirmButton: false,
      timer: 1800
    });
  });
</script>
<?php endif; ?>

</body>
</html>
