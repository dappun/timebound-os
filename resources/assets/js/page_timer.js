import 'vue-resource';

var VeeValidate = require('vee-validate');
var infiniteScroll = require('vue-infinite-scroll');

Vue.use(VeeValidate);
Vue.use(infiniteScroll)

/*

Template used for select2 component. Place this in your html file

<script type="text/x-template" id="select2-template">
    <select>
        <slot></slot>
    </select>
</script>

 */

var select2 = Vue.component('select2', {
    name: 'select2',
    props: ['options', 'value'],
    template: '#select2-template',
    mounted: function () {
        var vm = this;

        $(this.$el)
            .val(this.value)
            .select2({ data: this.options })
            .on('change', function () {
                vm.$emit('input', this.value)
            })
    },
    watch: {
        value: function (value) {
            // update value
            $(this.$el).val(value)
        },
        options: function (options) {
            // update options
            $(this.$el).select2({ data: options })
        }
    },
    destroyed: function () {
        $(this.$el).off().select2('destroy')
    }
});

window.select2 = select2;