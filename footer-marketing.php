<footer class="site-footer">
    <div class="site-footer__inner">

        <div class="site-footer__brand">
            <a href="<?php echo esc_url(home_url('/')); ?>">
                <img src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/images/logo-white.png"
                     alt="<?php bloginfo('name'); ?>"
                     width="140">
            </a>
            <p>Online first. Human when it matters.</p>
        </div>

        <div class="site-footer__links">
            <div class="site-footer__col">
                <h4>For Consumers</h4>
                <ul>
                    <li><a href="<?php echo esc_url(home_url('/get-a-quote/')); ?>">Get a Quote</a></li>
                    <li><a href="<?php echo esc_url(home_url('/insurance-agencies/')); ?>">Find an Agent</a></li>
                    <li><a href="<?php echo esc_url(home_url('/blog/')); ?>">Insurance Tips</a></li>
                </ul>
            </div>
            <div class="site-footer__col">
                <h4>For Agents</h4>
                <ul>
                    <li><a href="<?php echo esc_url(home_url('/pricing/')); ?>">Pricing</a></li>
                    <li><a href="<?php echo esc_url(home_url('/register/')); ?>">Create Account</a></li>
                    <li><a href="<?php echo esc_url(home_url('/login/')); ?>">Agent Login</a></li>
                </ul>
            </div>
            <div class="site-footer__col">
                <h4>Company</h4>
                <ul>
                    <li><a href="<?php echo esc_url(home_url('/about/')); ?>">About</a></li>
                    <li><a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a></li>
                    <li><a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Privacy Policy</a></li>
                </ul>
            </div>
        </div>

    </div>

    <div class="site-footer__bottom">
        <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
