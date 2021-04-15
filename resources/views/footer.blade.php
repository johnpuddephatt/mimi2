<footer class="footer mt-6">
  <div class="has-text-centered">
    <p class="menu-label">©{{ date("Y") }} Joy of Languages SRL<br> Milano, Via Pietro Mascagni n.31, 11461250968, MI - 2604182</p>
    <p class="menu-label"><a target="_blank" href="/terms">Terms and conditions</a>&nbsp;&nbsp;&nbsp;&nbsp; <a target="_blank" href="https://www.iubenda.com/privacy-policy/49813028">Privacy&nbsp;policy</a></p>
  </div>
</footer>

@if(session()->has('message'))
  <div class="notices is-bottom">
    <div role="alertdialog" class="toast is-success is-top is-autodismiss" style="">
      <div class="text">{{ session()->get('message') }}</div>
    </div>
  </div>
@endif

<script type="text/javascript">
  ! function(e, t, n) {
    function a() {
      var e = t.getElementsByTagName("script")[0],
        n = t.createElement("script");
      n.type = "text/javascript", n.async = !0, n.src = "https://beacon-v2.helpscout.net", e.parentNode.insertBefore(n, e)
    }
    if (e.Beacon = n = function(t, n, a) {
        e.Beacon.readyQueue.push({
          method: t,
          options: n,
          data: a
        })
      }, n.readyQueue = [], "complete" === t.readyState) return a();
    e.attachEvent ? e.attachEvent("onload", a) : e.addEventListener("load", a, !1)
  }(window, document, window.Beacon || function() {});
</script>
<script type="text/javascript">
  window.Beacon('config', {
    hideFABLabelOnMobile: true,
    color: '#33c2cf',
    display: {
      zIndex: '50',
      style: 'iconAndText',
      iconImage: 'buoy',
      text: 'Need help?'
    }
  });
  window.Beacon('init', 'd665d709-87f7-427b-994a-9089787faf0c')
</script>
