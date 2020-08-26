<template>
  <div class="hello">
    <img src="../assets/logo.png">
    <h1>{{ msg }}</h1>
    <h2>Essential Links</h2>
    {{ info }}
    {{ info2 }}
  </div>
</template>

<script>
export default {
  name: 'HelloWorld',
  data () {
    return {
      msg: 'Welcome to Your Vue.js App',
      info: '',
      info2: ''
    }
  },
  created () {
  liff
    .init({
      liffId: "1654776413-dpYy83Wb" // use own liffId
    })
    .then(() => {
      // Start to use liff's api
      this.displayLiffData()
    })
    .catch((err) => {
      // Error happens during initialization
      console.log(err.code, err.message);
    });
  },
  methods: {
    displayLiffData () {
      this.info = liff.getProfile() //このJSONの中にusetID入ってる
      const params = new URLSearchParams(decodeURIComponent(window.location.search.substring(1)))
      this.info2= "id : " + params.get('id') // /?key=hogeでしていしたhogeを取得できる
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1, h2 {
  font-weight: normal;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
</style>
