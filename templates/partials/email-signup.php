<?php $sign_up = get_field('email_signup', 'option'); ?>
<?php if($sign_up && $sign_up['title']) { ?>
  <div class="bg-primary mt-12 py-16 relative overflow-hidden" id="subscribe">
    <div class="absolute right-30 -bottom-20">
      <img src="<?php echo get_template_directory_uri() . '/dist/img/yellow-fern.png'; ?>" alt="Yellow Fern" class="w-64">
    </div>
    <div class="container space-y-8 text-center relative">
      <h2 class="h1 text-brand-white"><?php echo $sign_up['title']; ?></h2>
      <div class="space-y-8 text-brand-white content">
        <div class="text-xl"><?php echo $sign_up['content']; ?></div>
        <div><?php echo $sign_up['form_code']; ?></div>
        <div><?php echo $sign_up['subtext']; ?></div>
      </div>
    </div>
  </div>

  <script>
    (function() {
      var conversionFired = false;
      function attachObserver() {
        var signupRoot = document.querySelector('.gh-signup-root');
        if (!signupRoot) return;
        var iframe = signupRoot.querySelector('iframe');
        if (!iframe) return;
        var doc = iframe.contentDocument || iframe.contentWindow?.document;
        if (!doc || !doc.body) return;
        var observer = new MutationObserver(function() {
          if (conversionFired) return;
          var btn = doc.querySelector('button');
          if (btn && btn.innerText && btn.innerText.toLowerCase().includes('sent')) {
            conversionFired = true;
            observer.disconnect();
            gtag('event', 'conversion', {'send_to': 'AW-934395512/cC-CCM7UhI4cEPj8xr0D'});
          }
        });
        observer.observe(doc.body, { childList: true, subtree: true, characterData: true });
      }
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', attachObserver);
      } else {
        attachObserver();
      }
    })();
  </script>
<?php } ?>

