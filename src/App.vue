<template>
  <div id="app">
    <Detail 
      v-if="router('detail')"
      :rumorIdFromRank="rumorIdFromRank"
      @setPath="setPath"
    />
    <UserProfile 
      v-if="router('userProfile') || !path"
      @setPath="setPath"
    />
    <Quiz v-if="router('quiz')"/>
    <Ranking 
      v-if="router('ranking')"
      @setId="setId"
      @setPath="setPath"
    />
  </div>
</template>

<script>
import Detail from '@/components/Detail'
import UserProfile from '@/components/UserProfile'
import Quiz from '@/components/Quiz'
import Ranking from '@/components/Ranking'

export default {
  name: 'App',
  data () {
    return {
      path: '',
      rumorIdFromRank: ''
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
    },
    setPath (path) {
      this.path = path
    },
    setId (id) {
      this.rumorIdFromRank = id
      console.log(this.rumorIdFromRank)
    }
  },
  components: {
    Detail,
    UserProfile,
    Quiz,
    Ranking
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
  -webkit-overflow-scrolling : touch;
  background-color: #FAFAFA;
  overflow: scroll;
}

.mt5 {
    margin-top: 5px;
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

.ml20 {
    margin-left: 20px;
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

.red {
  color: #FF5205;
}

.green {
  color: #07B53B;
}
</style>
