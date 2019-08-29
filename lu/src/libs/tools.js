export const forEach = (arr, fn) => {
  if (!arr.length || !fn) { return }
  let i = -1
  let len = arr.length
  while (++i < len) {
    let item = arr[i]
    fn(item, i, arr)
  }
}

/**
 * @param {Array} arr1
 * @param {Array} arr2
 * @description 得到两个数组的交集, 两个数组的元素为数值或字符串
 */
export const getIntersection = (arr1, arr2) => {
  let len = Math.min(arr1.length, arr2.length)
  let i = -1
  let res = []
  while (++i < len) {
    const item = arr2[i]
    if (arr1.indexOf(item) > -1) { res.push(item) }
  }
  return res
}

/**
 * @param {Array} arr1
 * @param {Array} arr2
 * @description 得到两个数组的并集, 两个数组的元素为数值或字符串
 */
export const getUnion = (arr1, arr2) => {
  return Array.from(new Set([
    ...arr1,
    ...arr2
  ]))
}

/**
 * @param {Array} target 目标数组
 * @param {Array} arr 需要查询的数组
 * @description 判断要查询的数组是否至少有一个元素包含在目标数组中
 */
export const hasOneOf = (targetarr, arr) => {
  return targetarr.some(_ => arr.indexOf(_) > -1)
}

/**
 * @param {String|Number} value 要验证的字符串或数值
 * @param {*} validList 用来验证的列表
 * @description 判断要查询的字符串是一维数组中的一个值
 */
export function oneOf (value, validList) {
  for (let i = 0; i < validList.length; i++) {
    if (value === validList[i]) {
      return true
    }
  }
  return false
}

/**
 * @param {Number} timeStamp 判断时间戳格式是否是毫秒
 * @returns {Boolean}
 */
export const isMillisecond = timeStamp => {
  const timeStr = String(timeStamp)
  return timeStr.length > 10
}

/**
 * @param {Number} timeStamp 传入的时间戳
 * @param {Number} currentTime 当前时间时间戳
 * @returns {Boolean} 传入的时间戳是否早于当前时间戳
 */
export const isEarly = (timeStamp, currentTime) => {
  return timeStamp < currentTime
}

/**
 * @param {Number} num 数值
 * @returns {String} 处理后的字符串
 * @description 如果传入的数值小于10，即位数只有1位，则在前面补充0
 */
export const getHandledValue = num => {
  return num < 10
    ? '0' + num
    : num
}

/**
 * @param {Number} timeStamp 传入的时间戳
 * @param {Number} startType 要返回的时间字符串的格式类型，传入'year'则返回年开头的完整时间
 */
export const getDate = (timeStamp, startType) => {
  const d = new Date(timeStamp * 1000)
  const year = d.getFullYear()
  const month = getHandledValue(d.getMonth() + 1)
  const date = getHandledValue(d.getDate())
  const hours = getHandledValue(d.getHours())
  const minutes = getHandledValue(d.getMinutes())
  const second = getHandledValue(d.getSeconds())
  let resStr = ''
  if (startType === 'year') { resStr = year + '-' + month + '-' + date + ' ' + hours + ':' + minutes + ':' + second } else { resStr = month + '-' + date + ' ' + hours + ':' + minutes }
  return resStr
}

/**
 * @param {String|Number} timeStamp 时间戳
 * @returns {String} 相对时间字符串
 */
export const getRelativeTime = timeStamp => {
  // 判断当前传入的时间戳是秒格式还是毫秒
  const IS_MILLISECOND = isMillisecond(timeStamp)
  // 如果是毫秒格式则转为秒格式
  if (IS_MILLISECOND) { Math.floor(timeStamp /= 1000) }
  // 传入的时间戳可以是数值或字符串类型，这里统一转为数值类型
  timeStamp = Number(timeStamp)
  // 获取当前时间时间戳
  const currentTime = Math.floor(Date.parse(new Date()) / 1000)
  // 判断传入时间戳是否早于当前时间戳
  const IS_EARLY = isEarly(timeStamp, currentTime)
  // 获取两个时间戳差值
  let diff = currentTime - timeStamp
  // 如果IS_EARLY为false则差值取反
  if (!IS_EARLY) { diff = -diff }
  let resStr = ''
  const dirStr = IS_EARLY
    ? '前'
    : '后'
  // 少于等于59秒
  if (diff <= 59) { resStr = diff + '秒' + dirStr }
  // 多于59秒，少于等于59分钟59秒
  else if (diff > 59 && diff <= 3599) { resStr = Math.floor(diff / 60) + '分钟' + dirStr }
  // 多于59分钟59秒，少于等于23小时59分钟59秒
  else if (diff > 3599 && diff <= 86399) { resStr = Math.floor(diff / 3600) + '小时' + dirStr }
  // 多于23小时59分钟59秒，少于等于29天59分钟59秒
  else if (diff > 86399 && diff <= 2623859) { resStr = Math.floor(diff / 86400) + '天' + dirStr }
  // 多于29天59分钟59秒，少于364天23小时59分钟59秒，且传入的时间戳早于当前
  else if (diff > 2623859 && diff <= 31567859 && IS_EARLY) { resStr = getDate(timeStamp) } else { resStr = getDate(timeStamp, 'year') }
  return resStr
}

/**
 * 传入 秒，返回：xx天、xx时xx分xx秒
 * @param second_time
 * @returns {string}
 */
export const getDHMS = (second_time) => {
    var time = parseInt(second_time) + '秒'
    if (parseInt(second_time) > 60) {
        var second = parseInt(second_time) % 60
        var min = parseInt(second_time / 60)
        time = min + '分' + second + '秒'

        if (min > 60) {
            min = parseInt(second_time / 60) % 60
            var hour = parseInt(parseInt(second_time / 60) / 60)
            time = hour + '小时' + min + '分' + second + '秒'

            if (hour > 24) {
                hour = parseInt(parseInt(second_time / 60) / 60) % 24
                var day = parseInt(parseInt(parseInt(second_time / 60) / 60) / 24)
                time = day + '天' + hour + '小时' + min + '分' + second + '秒'
            }
        }
    }

    return time
}

/**
 * @returns {String} 当前浏览器名称
 */
export const getExplorer = () => {
  const ua = window.navigator.userAgent
  const isExplorer = (exp) => {
    return ua.indexOf(exp) > -1
  }
  if (isExplorer('MSIE')) { return 'IE' } else if (isExplorer('Firefox')) { return 'Firefox' } else if (isExplorer('Chrome')) { return 'Chrome' } else if (isExplorer('Opera')) { return 'Opera' } else if (isExplorer('Safari')) { return 'Safari' }
}

/**
 * @description 绑定事件 on(element, event, handler)
 */
export const on = (function () {
  if (document.addEventListener) {
    return function (element, event, handler) {
      if (element && event && handler) {
        element.addEventListener(event, handler, false)
      }
    }
  } else {
    return function (element, event, handler) {
      if (element && event && handler) {
        element.attachEvent('on' + event, handler)
      }
    }
  }
})()

/**
 * @description 解绑事件 off(element, event, handler)
 */
export const off = (function () {
  if (document.removeEventListener) {
    return function (element, event, handler) {
      if (element && event) {
        element.removeEventListener(event, handler, false)
      }
    }
  } else {
    return function (element, event, handler) {
      if (element && event) {
        element.detachEvent('on' + event, handler)
      }
    }
  }
})()

/**
 * 判断一个对象是否存在key，如果传入第二个参数key，则是判断这个obj对象是否存在key这个属性
 * 如果没有传入key这个参数，则判断obj对象是否有键值对
 */
export const hasKey = (obj, key) => {
  if (key) { return key in obj } else {
    let keysArr = Object.keys(obj)
    return keysArr.length
  }
}

/**
 * @param {*} obj1 对象
 * @param {*} obj2 对象
 * @description 判断两个对象是否相等，这两个对象的值只能是数字或字符串
 */
export const objEqual = (obj1, obj2) => {
  const keysArr1 = Object.keys(obj1)
  const keysArr2 = Object.keys(obj2)
  if (keysArr1.length !== keysArr2.length) { return false } else if (keysArr1.length === 0 && keysArr2.length === 0) { return true }
  /* eslint-disable-next-line */
  else
    return !keysArr1.some(key => obj1[key] != obj2[key])
}

// 获取当前时间，格式YYYY-MM-DD
export const getNowFormatDate = () => {
  var date = new Date()
  var seperator1 = '-'
  var year = date.getFullYear()
  var month = date.getMonth() + 1
  var strDate = date.getDate()
  if (month >= 1 && month <= 9) {
    month = '0' + month
  }
  if (strDate >= 0 && strDate <= 9) {
    strDate = '0' + strDate
  }
  var currentdate = year + seperator1 + month + seperator1 + strDate
  return currentdate
}

// 获取当前日期时间，格式YYYY-MM-DD H:i:s
export const getNowDateTimeWeek = (date_sign = '-', time_sign = ':') => {
  var date = new Date()
  // var date_sign = "-";
  // var date_and_time_sign = "=";
  // var time_sign = ":";
  var year = date.getFullYear() // 年
  var month = date.getMonth() + 1 // 月
  var day = date.getDate() // 日
  var hour = date.getHours() // 时
  var minutes = date.getMinutes() // 分
  var seconds = date.getSeconds() // 秒
  var weekArr = [
    '星期一',
    '星期二',
    '星期三',
    '星期四',
    '星期五',
    '星期六',
    '星期天'
  ]
  var week = weekArr[date.getDay()]
  // 给一位数数据前面加 “0”
  if (month >= 1 && month <= 9) {
    month = '0' + month
  }
  if (day >= 0 && day <= 9) {
    day = '0' + day
  }
  if (hour >= 0 && hour <= 9) {
    hour = '0' + hour
  }
  if (minutes >= 0 && minutes <= 9) {
    minutes = '0' + minutes
  }
  if (seconds >= 0 && seconds <= 9) {
    seconds = '0' + seconds
  }
  var dateStr = year + date_sign + month + date_sign + day
  var time = hour + time_sign + minutes + time_sign + seconds

  return [dateStr, time, week]
}

// 根据 url下载文件
export const downloadFilePassUrl = (url) => {
  var $form = $('<form method="GET"></form>')
  $form.attr('action', url)
  $form.appendTo($('body'))
  $form.submit()
}
