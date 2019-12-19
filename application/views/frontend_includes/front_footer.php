<footer>
        <div class="footer-wrap inner-padding wow fadeIn footer-wrap-green">
            <div class="container">
                <div class="footer-bottom">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <div class="follow">
                                <h4>Follow us on Social Media</h4>
                            </div>
                             <div class="follow">
                                <a href="https://www.facebook.com/pages/category/Information-Technology-Company/ConnektUs-412365742629253/" target="_blank"><i class="fa fa-facebook" target="_blank"></i></a>
                                <a href="https://twitter.com/ConnektUsJobs" target="_blank"><i class="fa fa-twitter"></i></a>
                                <a href="https://www.linkedin.com/company/connektusjobs" target="_blank"><i class="fa fa-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Footer Area Ends -->
    <!-- Copyright Area Starts -->
    <div class="copyright-area">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-12 col-sm-12 text-center">
                    <small><?php echo COPYRIGHT;?><a href="<?php echo base_url();?>"><?php echo ' '.SITE_TITLE;?></a>.  All Rights Reserved.</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="<?php echo base_url().UC_ASSETS_JS;?>theme.js"></script>
    <script src="<?php echo base_url().ADMIN_THEME; ?>plugins/tostar/toastr.min.js"></script>
    <?php if(ENVIRONMENT == 'production'){ ?>
    <script>
        //Facbook pixel click event(Lead) to track ios and andriod click event
        $( '#androidDownload' ).click(function() {
            fbq('track', 'Lead', {
                value: 0, //0: for android
            });
        });

        $( '#iosDownload' ).click(function() {
            fbq('track', 'Lead', {
                value: 1, //1: for ios
            });
        });
    </script>
    <?php } ?>
    <script>
        function initMap() {
            var latlng = new google.maps.LatLng(-25.344, 131.036);
            map = new google.maps.Map(document.getElementById('google_map'), {
            center: latlng,
            zoom: 12
            });
        }

    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCgimAPCWXLlHXCNvJBir08BYPDpuMjyFs&callback=initMap"></script>
    <script src="<?php echo base_url().UC_ASSETS_JS;?>custom.js?v=12"></script>
 </body>
</html>