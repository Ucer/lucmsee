import {login, logout, getUserInfo} from '@/api/user'
import {setTokenToCookies, getTokenFromCookies} from '@/libs/util'

export default {
  state: {
    userId: '',
    email: '',
    name: '无',
    avatorImgPath: '',
    access: '',
    accessToken: getTokenFromCookies(),
    accessTokenType:'',
    unReadMessage: 0
  },
  mutations: {
    setUserId(state, value) {
      state.userId = value
    },
    setUnReadMessage(state, value) {
      state.unReadMessage = value
    },
    setName(state, value) {
      state.name = value
    },
    setEmail(state, value) {
      state.email = value
    },
    setAvator(state, value) {
      state.avatorImgPath = value
    },
    setAccess(state, value) {
      state.access = value
    },
    setAccessToken(state, data) {
      let token = ''
      if (data.token) {
        state.accessTokenType = data.token.token_type
        token = data.token.token_type + ' ' + data.token.access_token
      }
      state.accessToken = token
      setTokenToCookies(token)
    }
  },
  actions: {
    // 登录
    handleLogin({
      commit
    }, {email, password, captcha, captcha_key}) {
      email = email.trim()
      return new Promise((resolve, reject) => {
        login({email, password, captcha, captcha_key}).then(res => {
          const data = res.data
          commit('setAccessToken', data)
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    // 退出登录
    handleLogOut({state, commit}) {
      return new Promise((resolve, reject) => {
        logout(state.token).then(() => {
          commit('setAccessToken', '')
          resolve()
        }).catch(err => {
          reject(err)
        })
      })
    },
    // 获取用户相关信息
    getUserInfoExcute({state, commit}) {
      return new Promise((resolve, reject) => {
        getUserInfo().then(res => {
          const data = res.data
          commit('setEmail', data.email)
          commit('setAvator', data.avatar)
          commit('setName', data.name)
          commit('setUserId', data.user_id)
          commit('setAccess', data.roles)
          commit('setUnReadMessage', data.unread_message)
          resolve(data)
        }).catch(err => {
          reject(err)
        })
      })
    }
  }
}
