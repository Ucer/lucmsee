<style lang="less">
.count-style {
    font-size: 50px;
}
</style>
<template>
<div>
  <Row :gutter="20">
    <i-col span="4" v-for="(infor, i) in inforCardData" :key="`infor-${i}`" style="height: 120px;">
      <infor-card shadow :color="infor.color" :icon="infor.icon" :icon-size="36">
        <a :href="infor.url">
          <count-to :end="infor.count" count-class="count-style" />
        </a>
        <p>{{ infor.title }}</p>
      </infor-card>
    </i-col>
  </Row>
  <Row style="margin-top: 20px;">
    <div class="demo-spin-container" v-if="tableLoading">
      <Spin fix>
        <Icon type="load-c" size=18 class="spin-icon-load"></Icon>
        <div>加载中...</div>
      </Spin>
    </div>
    <i-col>
      <Card shadow>
        <div id="user-echart-data" style="height:400px;"></div>
      </Card>
    </i-col>

  </Row>

</div>
</template>

<script>
import echarts from 'echarts'
import userChartTheme from './user-chart/theme.json'

import InforCard from './components/info-card'
import CountTo from '_c/count-to'
import ChartBar from './user-chart/bar.vue'

import {
  getStatisticsData
} from '@/api/home'
export default {
  name: 'home',
  components: {
    InforCard,
    CountTo,
  },
  data() {
    return {
      tableLoading: true,
      inforCardData: {},
    }
  },
  mounted() {
    this.getStatisticsDataExcute()

  },
  methods: {
    getStatisticsDataExcute() {
      let t = this
      getStatisticsData().then(res => {
        t.tableLoading = false
        let res_data = res.data
        this.userEchart(res_data.user_echart_data)
        t.inforCardData = [{
          title: '用户数量',
          icon: 'ios-people',
          count: res_data.user_count,
          color: '#009688',
          url: '#/administrator-list'
        }, {
          title: '文章数量',
          icon: 'ios-paper',
          count: res_data.article_count,
          color: '#2D8cF0',
          url: '#/article-list'
        }, ]
      })
    },
    userEchart(res_data) {
      // 基于准备好的dom，初始化echarts实例
      var myChart = echarts.init(document.getElementById('user-echart-data'), userChartTheme);

      // 指定图表的配置项和数据
      var option = {
        title: {
          text: '最近七天用户注册量',
          x: 'center'
        },
        tooltip: {},
        legend: {
          data: ['注册用户']
        },
        xAxis: {
          data: res_data['keys']
        },
        yAxis: {},
        series: [{
          name: '注册用户数',
          type: 'bar',
          data: res_data['values']
        }]
      };

      // 使用刚指定的配置项和数据显示图表。
      myChart.setOption(option);
    }
  }
}
</script>
