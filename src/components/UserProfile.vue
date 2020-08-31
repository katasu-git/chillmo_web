<template>
  <div class="userProflie">
    <div class="header">
        <img class="userPicture" :src="userProfile.pictureUrl" />
        <div class="mt20" />
        <div class="userName">{{userProfile.displayName}}</div>
        <div class="createdAt">{{userLog.created_at}} から利用中</div>
    </div>
    <div v-if="userLog.test_group == 1" class="inner"><!--groupで機能を制限-->
        <div class="componet">
            <div class="mt20" />
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
                    <span class="num"><span class="green">{{userLog.check_continue}}</span><span class="subtitle">日</span></span>
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
    <div
        class="enquete"
        v-if="userLog && !userLog.gender"
    >
        <div>【初回アンケート】</div>
        <div>あなたは男性ですか？女性ですか？</div>
        <div class="mt20" />
        <div class="buttons">
            <span 
                class="button_man"
                @click="()=>{this.gender = '男性'}"
            >男性</span>
            <span 
                class="button_woman"
                @click="()=>{this.gender = '女性'}"
            >女性</span>
        </div>
        <div class="mt20" />
        <div class="which_select">選択中：{{gender}}</div>
        <div class="mt30" />
        <div class="button" @click="submitGender()">回答を送信</div>
    </div>
    <div
        class="enquete_done mt20" 
        v-else
    >アンケート回答済み：{{userLog.gender}}</div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'UserProfile',
  data () {
    return {
        userProfile: JSON,
        userLog: JSON,
        gender: '',
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
            this.userLog = response.data[0]
        })
    },
    getHalf (num) {
        if(!num) {
            return ''
        }
        return Math.ceil(num) 
        // return parseInt(num / 2, 10)
    },
    setPath () {
        if(this.checkEnd()) {
            this.$emit('setPath', 'quizEnd')
        } else {
            this.$emit('setPath', 'quiz')
        }
    },
    checkEnd () {
        console.log(this.userLog.answer_today > 9 && this.getNowYMD() == this.userLog.last_answer_date)
        if(this.userLog.answer_today > 9 && this.getNowYMD() == this.userLog.last_answer_date) {
            return true
        } else {
            return false
        }
    },
    getNowYMD(){
        var dt = new Date();
        var y = dt.getFullYear();
        var m = ("00" + (dt.getMonth()+1)).slice(-2);
        var d = ("00" + dt.getDate()).slice(-2);
        var result = y + "-" + m + "-" + d;
        return result;
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
    submitGender() {
        if(!this.gender) {
            return
        }
        const url = "https://www2.yoslab.net/~nishimura/chillmoWeb/static/PHP/submitGender.php"
        let params = new URLSearchParams();
        params.append("line_user_id", this.userProfile.userId)
        params.append("gender", this.gender)
        axios.post(url, params)
        .then((response)=>{
            this.userLog = response.data[0]
        })
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.userProflie {
    width: 100%;
    height: 100%;
    color: #4B4B4B;
}

.header {
    background-color: #07B53B;
    color: #FFF;
    width: 100%;
    padding: 20px 0;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.inner, .enquete, .enquete_done {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
}

.userPicture {
    width: 70px;
    height: 70px;
    border-radius: 100%;
}

.userName, .which_select {
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

.buttons {
    width: 100%;
    display: flex;
    align-items: center;
}

.button_man, .button_woman {
    width: 50%;
    background-color: #6AC0C8;
    color: #FFF;
    border-radius: 3px;
    height: 45px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;
    padding: 20px;
    margin: 0px 10px;
}

.button_woman {
    background-color: #EF7943;
}
</style>
