<template>
  <div id="app">
    <Detail v-if="router('detail')"/>
    <UserProfile v-if="router('userProfile')"/>
    <Quiz v-if="router('quiz')"/>
  </div>
</template>

<script>
import Detail from '@/components/Detail'
import UserProfile from '@/components/UserProfile'
import Quiz from '@/components/Quiz'

export default {
  name: 'App',
  data () {
    return {
      path: ''
    }
  },
  created () {
      const params = new URLSearchParams(decodeURIComponent(window.location.search.substring(1)))
      this.path = params.get('path') // /?key=hogeでしていしたhogeを取得できる
  },
  methods: {
    router (comp) {
      if(this.path == comp) {
        return true;
      } else {
        return false;
      }
    }
  },
  components: {
    Detail,
    UserProfile,
    Quiz
  }
}
</script>

<style>
html, body, #app {
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  background-color: #FAFAFA;
}

.mt10 {
    margin-top: 10px;
}

.mt20 {
    margin-top: 20px;
}

.mt30 {
    margin-top: 30px;
}

.mt40 {
    margin-top: 40px;
}

.mt50 {
    margin-top: 50px;
}

.ml10 {
    margin-left: 10px;
}

.ml50 {
    margin-left: 50px;
}

.mb20 {
    padding-bottom: 20px;
}

.border {
  height: 1px;
  width: 100%;
  background-color: #E5E5E5;
}
</style>
