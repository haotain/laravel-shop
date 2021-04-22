<template>
  <div>
    <div class="">
      <select-district @change="onDistrictChanged" />
    </div>
    <slot :province="province" :city="city" :district="district"></slot>
  </div>
</template>

<script>

  import SelectDistrict from './SelectDistrict.vue'

  export default {
    data() {
      return {
        province: '', // 省
        city: '',     // 市
        district: ''  // 区
      }
    },
    components: {
      SelectDistrict
    },
    methods: {
      // 把参数 val 保存的值保存导组件数据中
      onDistrictChanged(val) {
        console.log(val,this.province)
        if (val.length === 3) {
          this.province = val[0];
          this.city = val[1];
          this.district = val[2];
        }
      }
    }
  }
</script>

