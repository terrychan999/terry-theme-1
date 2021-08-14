
        </main>
    </div><!-- content -->
    <footer id="footer">
        <?php
            // use admin public display name
            $user_info = get_userdata(1);
            $display_name = $user_info->display_name;
            echo date('Y') . " " . $display_name . ".";
        ?>
    </footer>
</div><!-- container -->
<?php wp_footer(); ?>
</body>
</html>