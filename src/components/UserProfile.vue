<template>
  <div class="userProflie">
    <div class="header">
        <div class="mt20" />
        <img class="userPicture" :src="userProfile.pictureUrl" />
        <div class="mt20" />
        <div class="userName">{{userProfile.displayName}}</div>
        <div class="createdAt">{{userLog.created_at}} から利用中</div>
        <div class="mb20" />
    </div>
    <div v-if="userLog.group == 0" class="inner"><!--groupで機能を制限-->
        <div class="componet">
            <div class="mt30" />
            <div class="title">流言のチェック</div>
            <div class="mt20" />
            <div class="subComponent">
                <div class="nums">
                    <div class="subtitle">累計</div>
                    <div class="mt10" />
                    <span class="num">{{getHalf(userLog.check_sum)}}<span class="subtitle">件</span></span>
                </div>
                <div class="nums">
                    <div class="subtitle">今週</div>
                    <div class="mt10" />
                    <span class="num">{{getHalf(userLog.check_weekly)}}<span class="subtitle">件</span></span>
                </div>
                <div class="nums">
                    <div class="subtitle">連続</div>
                    <div class="mt10" />
                    <span class="num">{{userLog.check_continue}}<span class="subtitle">日</span></span>
                </div>
            </div>
        </div>
        <div class="mt30" />
        <div class="border" />
        <div class="componet">
            <div class="mt30" />
            <div class="title">流言クイズ</div>
            <div class="mt20" />
            <div class="subComponent">
                <div class="nums">
                    <div class="subtitle">回答数</div>
                    <div class="mt10" />
                    <span class="num">{{userLog.answer_sum}}<span class="subtitle">件</span></span>
                </div>
                <div class="nums">
                    <div class="subtitle">正答率</div>
                    <div class="mt10" />
                    <span class="num">{{getCorrectPer()}}<span class="subtitle">%</span></span>
                </div>
            </div>
        </div>
        <div class="mt50" />
        <div 
            class="button"
            @click="setPath()"
        >クイズに挑戦する</div>
        <div class="mb20" />
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'UserProfile',
  data () {
    return {
        userProfile: JSON,
        userLog: ''
    }
  },
  created () {
    this.liffInit()
    liff.ready.then(() => {
        // do something you want when liff.init finishes
        if(!this.checkLogIn()) {
            liff.login();
        }
        this.getUserProfile()
    })
  },
  methods: {
    liffInit () {
        liff.init({liffId: "1654776413-dpYy83Wb"})
    },
    checkLogIn  () {
        return liff.isLoggedIn()
    },
    getUserProfile () {
        liff.getProfile()
        .then((response) => {
            this.userProfile = response //userId,displayName,pictureUrl,statusMessage
            this.getUserLog()
        })
        .catch(error => {
            console.log(error)
        })
    },
    getUserLog () {
        const url = "https://www2.yoslab.net/~nishimura/chillmoWeb/static/PHP/getUserLog.php"
        let params = new URLSearchParams();
        params.append("line_user_id", this.userProfile.userId)
        axios.post(url, params)
        .then((response)=>{
            // console.log(response.data)
            this.userLog = response.data[0]
            console.log(this.userLog)
        })
    },
    getHalf (num) {
        if(!num) {
            return ''
        }
        return parseInt(num / 2, 10)
    },
    setPath () {
        this.$emit('setPath', 'quiz')
    },
    getCorrectPer () {
        if(this.userLog.answer_sum == 0) {
            return 0
        }
        if(this.userLog) {
            const per = (this.userLog.answer_correct / this.userLog.answer_sum) * 100
            return parseInt(per, 10);
        } else {
            return ''
        }
    },
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.userProflie {
    width: 100%;
    height: 100%;
    color: #4B4B4B;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.inner {
    height: calc(100% - 40px);
    width: calc(100% - 40px);
}

.header {
    background-color: #07B53B;
    color: #FFF;
    width: 100%;
    height: 240px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.userPicture {
    width: 70px;
    height: 70px;
    border-radius: 100%;
}

.userName {
    font-size: 20px;
    font-weight: bold;
}

.createdAt {
    font-size: 12px;
}

.componet {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.subComponent {
    display: flex;
    align-items: center;
}

.title {
    font-size: 18px;
    font-weight: bold;
}

.nums {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0 20px;
}

.subtitle {
    font-size: 12px;
}

.num {
    font-size: 24px;
    font-weight: bold;
}

.button {
    width: 100%;
    background-color: #3498CB;
    color: #FFF;
    border-radius: 3px;
    height: 45px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;
}
</style>
