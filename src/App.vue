<template>
  <div id="app">
    <Detail/>
  </div>
</template>

<script>
import Detail from '@/components/Detail'

export default {
  name: 'App',
  components: {
    Detail
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

.ml10 {
    margin-left: 10px;
}

.ml50 {
    margin-left: 50px;
}

.mb20 {
    padding-bottom: 20px;
}
</style>
