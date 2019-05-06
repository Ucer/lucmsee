import { login, logout, getUserInfo } from '@/api/user'
import { setTokenToCookies, getTokenFromCookies } from '@/libs/util'

export default {
  state: {
    user_id: '',
    email: '',
    nickname: '',
    real_name: '',
    avator: '',
    access: '',
    accessToken: getTokenFromCookies(),
    accessTokenType: '',
    unread_message: 0,
    system_version: '0'
  },
  mutations: {
    setAnyState (state, array) {
      state[array[0]] = array[1]
    },
    setAccessToken (state, data) {
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
    handleLogin ({
      commit
    }, { email, password, captcha, captcha_key }) {
      email = email.trim()
      return new Promise((resolve, reject) => {
        login({ email, password, captcha, captcha_key }).then(res => {
          const data = res.data
          commit('setAccessToken', data)
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    // 退出登录
    handleLogOut ({ state, commit }) {
      return new Promise((resolve, reject) => {
        logout(state.accessToken).then(() => {
          commit('setAccessToken', '')
          resolve()
        }).catch(err => {
          reject(err)
        })
      })
    },
    // 获取用户相关信息
    getUserInfoExcute ({ state, commit }) {
      return new Promise((resolve, reject) => {
        getUserInfo().then(res => {
          const data = res.data
          commit('setAnyState', ['avator', data.avatar])
          commit('setAnyState', ['email', data.email])
          commit('setAnyState', ['user_id', data.id])
          commit('setAnyState', ['access', data.roles])
          commit('setAnyState', ['nickname', data.nickname])
          commit('setAnyState', ['real_name', data.real_name])
          commit('setAnyState', ['unread_message', data.unread_message])
          commit('setAnyState', ['system_version', data.system_version])
          resolve(data)
        }).catch(err => {
          reject(err)
        })
      })
    }
  }
}
