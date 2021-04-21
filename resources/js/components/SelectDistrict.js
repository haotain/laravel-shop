// 加载 china-area-data 库的数据
const addressData = require('china-area-data/v5/data')

// 引入 lodash，lodash 是一个实用工具库，提供了很多常用的方法
import _ from 'lodash'

// 注册一个名为 select-district 的 Vue 组件

Vue.component('select-district', {
  props: {
    initValue: {
      type: Array,
      default : () => []
    }
  },
  data() {
    return {
      provinces: addressData['86'], // 省列表
      cities: {}, // 城市列表
      districts: {}, // 地区列表
      provinceId: '', // 当前选中的省
      cityId: '', // 当前选中的市
      districtId: '', // 当前选中的区
    }
  },
  // 定义观察器，对应属性变更时会触发对应的观察器函数
  watch: {
    // 当选择的省发生改变时触发
    provinceId(newVal) {
      if (!newVal) {
        this.cities = {}
        this.cityId = ''
        return
      }

       // 将城市列表设为当前省下的城市
      this.cities = addressData[newVal]
    }
  }
})
