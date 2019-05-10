
<template>
<Layout style="height: 100%" class="main">
  <Sider hide-trigger collapsible :width="256" :collapsed-width="64" v-model="collapsed" class="left-sider" :style="{overflow: 'hidden'}">
    <side-menu accordion ref="sideMenu" :active-name="$route.name" :collapsed="collapsed" @on-select="turnToPage" :menu-list="menuList">
      <Row class="sidebar-title">
        <Col span="12" class="title-text hidden-mobile">{{ systemTitle }}</Col>
        <Col span="12" class="notify-text">
        <Badge :count="stateUnreadMessage">
          <a  @click="quickToRouter('adminMessages',{})" :title="stateUnreadMessage+' 条未读消息'" v-if="stateUnreadMessage > 0">
            <Icon type="ios-notifications" size="26"></Icon>
          </a>
          <a @click="quickToRouter('adminMessages',{})" title="暂无未读消息" v-else>
            <Icon type="ios-notifications" size="26"></Icon>
          </a>
        </Badge>
        </Col>
      </Row>
    </side-menu>
  </Sider>
  <Layout>
    <Header class="header-con">
      <header-bar :collapsed="collapsed" @on-coll-change="handleCollapsedChange">
        <user />
        <language v-if="$config.useI18n" @on-lang-change="setLocal" style="margin-right: 10px;" :lang="local" />
        <Dropdown style="margin-right:10px" @on-click="useTools">
          <a href="javascript:void(0)">
            工具栏
            <Icon type="ios-arrow-down"></Icon>
          </a>
          <DropdownMenu slot="list">
            <DropdownItem name='photo_editor'>图片裁剪</DropdownItem>
          </DropdownMenu>
        </Dropdown>
<!--        <error-store v-if="$config.plugin['error-store'] && $config.plugin['error-store'].showInHeader" :has-read="hasReadErrorPage" :count="errorCount"></error-store>-->
      </header-bar>
    </Header>
    <Content class="main-content-con">
      <Layout class="main-layout-con">
        <div class="tag-nav-wrapper">
          <tags-nav :value="$route" @input="handleClick" :list="tagNavList" @on-close="handleCloseTag" />
        </div>
        <Content class="content-wrapper">
          <keep-alive :include="cacheList">
            <router-view />
          </keep-alive>
          <ABackTop :height="100" :bottom="80" :right="50" container=".content-wrapper"></ABackTop>
        </Content>
      </Layout>
    </Content>
    <Footer class="text-center"><span class="blod-font">{{copyRight}}{{stateSystemVersion}}</span> - 2019 &copy; Ucer</Footer>
  </Layout>
</Layout>
</template>
<script>
import SideMenu from './components/side-menu'
import HeaderBar from './components/header-bar'
import TagsNav from './components/tags-nav'
import User from './components/user'
import ABackTop from './components/a-back-top'
import Fullscreen from './components/fullscreen'
import Language from './components/language'
import ErrorStore from './components/error-store'
import {
  mapMutations,
  mapActions,
  mapGetters
} from 'vuex'
import {
  getNewTagList,
  routeEqual
} from '@/libs/util'
import routers from '@/router/routers'
import minLogo from '@/assets/images/logo-min.jpg'
import maxLogo from '@/assets/images/logo.jpg'
import './main.less'
export default {
  name: 'Main',
  components: {
    SideMenu,
    HeaderBar,
    Language,
    TagsNav,
    Fullscreen,
    ErrorStore,
    User,
    ABackTop
  },
  data () {
    return {
      collapsed: false,
      minLogo,
      maxLogo,
      isFullscreen: false,
      systemTitle: window.systemConfigIndexFile.systemTitle,
      title: window.systemConfigIndexFile.title,
      copyRight: window.systemConfigIndexFile.copyRight
    }
  },
  computed: {
    ...mapGetters([
      'errorCount'
    ]),
    tagNavList () {
      return this.$store.state.app.tagNavList
    },
    tagRouter () {
      return this.$store.state.app.tagRouter
    },
    userAvator () {
      return this.$store.state.user.avatorImgPath
    },
    cacheList () {
      const list = ['ParentView', ...this.tagNavList.length ? this.tagNavList.filter(item => !(item.meta && item.meta.notCache)).map(item => item.name) : []]
      return list
    },
    menuList () {
      return this.$store.getters.menuList
    },
    local () {
      return this.$store.state.app.local
    },
    hasReadErrorPage () {
      return this.$store.state.app.hasReadErrorPage
    },
    stateUnreadMessage () {
      return this.$store.state.user.unread_message
    },
    stateSystemVersion () {
      return 'V' + this.$store.state.user.system_version
    }
  },
  methods: {
    ...mapMutations([
      'setBreadCrumb',
      'setTagNavList',
      'addTag',
      'setLocal',
      'setHomeRoute',
      'closeTag'
    ]),
    ...mapActions([
      'handleLogin'
    ]),
    turnToPage (route) {
      let {
        name,
        params,
        query
      } = {}
      if (typeof route === 'string') {
        name = route
      } else {
        name = route.name
        params = route.params
        query = route.query
      }
      if (name.indexOf('isTurnByHref_') > -1) {
        window.open(name.split('_')[1])
        return
      }
      this.$router.push({
        name,
        params,
        query
      })
    },
    handleCollapsedChange (state) {
      this.collapsed = state
    },
    handleCloseTag (res, type, route) {
      if (type !== 'others') {
        if (type === 'all') {
          this.turnToPage(this.$config.homeName)
        } else {
          if (routeEqual(this.$route, route)) {
            this.closeTag(route)
          }
        }
      }
      this.setTagNavList(res)
    },
    handleClick (item) {
      this.turnToPage(item)
    },
    useTools (key) {
      switch (key) {
        case 'photo_editor':
          window.open(window.baseUrl + '/' + key)
          break
        default:
      }
    },
    quickToRouter (name, param) {
      this.$router.push({
        name: name,
        params: param
      })
    }
  },
  watch: {
    '$route' (newRoute) {
      const {
        name,
        query,
        params,
        meta
      } = newRoute
      this.addTag({
        route: {
          name,
          query,
          params,
          meta
        },
        type: 'push'
      })
      this.setBreadCrumb(newRoute)
      this.setTagNavList(getNewTagList(this.tagNavList, newRoute))
      this.$refs.sideMenu.updateOpenName(newRoute.name)
    }
  },
  mounted () {
    /**
     * @description 初始化设置面包屑导航和标签导航
     */
    this.setTagNavList()
    this.setHomeRoute(routers)
    this.addTag({
      route: this.$store.state.app.homeRoute
    })
    this.setBreadCrumb(this.$route)
    // 设置初始语言
    this.setLocal(this.$i18n.locale)
    // 如果当前打开页面不在标签栏中，跳到homeName页
    if (!this.tagNavList.find(item => item.name === this.$route.name)) {
      this.$router.push({
        name: this.$config.homeName
      })
    }
  }
}
</script>
