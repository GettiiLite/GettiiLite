<div id='loading'>  
    <div class="modal-overlay" v-show="show">
        <div class="modal-loading">
            <div class="loader-gl">G
                <div class="inner c1"></div>
                <div class="inner c2"></div>
                <div class="inner c3"></div>
            </div>
        </div>
    </div>
</div>
<script>
    Vue.config.devtools = true;
    var loading = new Vue({
        el: '#loading',
        data: {
            show: true,
        },
        methods: {
            openLoading: function(){
                this.show = true
            },
            closeLoading: function(){
                setTimeout(function(){loading.show = false}, 1000);
            }
        }
    })
</script><?php /**PATH /home/vagrant/code/gettiilite/resources/views/components/loading.blade.php ENDPATH**/ ?>