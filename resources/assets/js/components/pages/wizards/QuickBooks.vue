<template>
  <div class="wrap-wizard md-layout md-alignment-center-center">
    <stepper>
      <template slot="nav">
        <step-icon
          v-model="currentStep"
          :step-number="1"
          :lable="'Step 1'"
          :disabled="true"
        />
        <step-icon
          v-model="currentStep"
          :step-number="2"
          :lable="'Step 2'"
          :disabled="true"
        />
      </template>
      <template slot="body">
        <!-- step 1 -->
        <step-body
          v-model="currentStep"
          :step-number="1"
        >
          <div class="mt-4">
            <h3>QuickBook Setup</h3>
            <p class="text-justify">
              TicSol is designed to work with QuickBooks Online. Integrating TicSol and Quickbooks
              Online provides the following benefits: 
              <br>
              <br>1. Easy setup.
              <br>2. Create Time Activities from TicSol Timesheets.
              <br>3. Create Bills from TicSol reimbursements.
              <br>4. Create Vendors from TicSol Clients.
              <br>
            </p>
            <button
              type="button"
              class="btn btn-success"
              :disabled="isLoading"
              @click="connectToQuickbook"
            >
              {{ isLoading ? 'Processing, Please wait...' : 'Connect To QuickBook' }}
            </button>
          </div>
        </step-body>

        <!-- step 2 -->
        <step-body
          v-model="currentStep"
          :step-number="2"
        >
          <div class="mt-4">
            <h3>Success</h3>
            <p class="text-justify">
              Your <b>{{ companyName }}</b> has been successfuly connected to TicSol. Continue the 
              <router-link :to="{ name:'clientWizard' }">
                Client Wizard
              </router-link>
              or go 
              <router-link :to="{ name:'home' }">
                Home
              </router-link>.
            </p>           
          </div>
        </step-body>
      </template>
    </stepper>

    <!-- loading screen -->
    <div
      class="wrap-loading"
      v-show="isLoading"
    >
      <div class="loading-box shadow-sm">
        <div>
          <div
            class="spinner-border"
            role="status"
          >
            <span class="sr-only">Loading...</span>
          </div>
        </div>
        <div class="caption">
          {{ loadingMsg }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { api } from "../../../api/http";
import env from '../../../utils/env';
import Stepper from "../../base/formStepper/Stepper";
import StepBody from "../../base/formStepper/StepBody";
import StepIcon from "../../base/formStepper/StepIcon";

export default {
  name: "QuickBook",

  components: {
    stepper: Stepper,
    "step-body": StepBody,
    "step-icon": StepIcon
  },

  data() {
    return {
      isLoading: false,
      loadingMsg: "",
      currentStep: 1,
      firstStep: 1,
      lastStep: 2,
      companyinfo: "",

      // auth
      authEndPoint: "https://appcenter.intuit.com/connect/oauth2",
      clientID: "ABVz6DhHFhZcfqM3rmeQNtSYyfEzquOzy5LXC94dqQkYaWUSR5",
      responseType: "code",
      scope:
        "com.intuit.quickbooks.accounting openid profile email phone address",
      redirectURI: env() === 'local' ? "https://server.dev/wizard/quickbooks" : "https://app.ticsol.com.au/wizard/quickbooks",
      csrf: "1234"
    };
  },

  computed:{
    companyName:function(){
      if(this.companyinfo)
        return this.companyinfo.CompanyName.toUpperCase();
      else return "";
    }
  },

  beforeRouteEnter(to, from, next) {
    next(vm => {
      vm.checkForCode();
    });
  },

  methods: {
    connectToQuickbook() {
      let url =
        `${this.authEndPoint}?` +
        `client_id=${this.clientID}&` +
        `response_type=${this.responseType}&` +
        `redirect_uri=${this.redirectURI}&` +
        `scope=${this.scope}&` +
        `state=${this.csrf}`;

      location.replace(url);
    },

    checkForCode() {
      let code = this.$route.query.code;
      let state = this.$route.query.state;
      let companyId = this.$route.query.realmId;

      if (code) {
        if (state === this.csrf) {
          this.exchangeCodeForToken(code, companyId);
        } else {
          throw new Error("Invalid csrf token.");
        }
      }
    },

    async exchangeCodeForToken(code, companyId = null) {
      this.isLoading = true;
      this.loadingMsg = "requesting access token from QiuckBooks...";
      await api
        .get({
          url: `/api/quickbooks/token/${code}${
            companyId ? `/${companyId}` : ""
          }`
        })
        .catch(error => {
          console.log(error);
        });

      this.loadingMsg = "fetching company information from QiuckBooks...";
      await api
        .get({
          url: "/api/quickbooks/companyinfo"
        })
        .then(res => {
          this.companyinfo = res.data;
          this.currentStep++;
        })
        .catch(error => {
          console.log(error);
        });

        this.isLoading = false;
    }    
  }
};
</script>

<style scoped>
.wrap-wizard {
  background-color: rgba(255, 255, 255, 0.9);
  padding: 30px;
  width: 50%;
  height: 70%;
  border-radius: 2px;
  position: relative;
}

.wrap-loading {
  left: 0px;
  top: 0px;
  width: 100%;
  height: 100%;
  position: absolute;
  background-color: rgba(0, 0, 0, 0.15);
  z-index: 9;
}

.loading-box {
  left: 50%;
  top: 50%;
  width: 17vw;
  height: auto;
  padding: 1vw;
  position: relative;
  display: flex;
  align-items: center;
  border-radius: 0px;
  transform: translate(-50%, -50%);
  background-color: white;
}

.loading-box .spinner-border {
  width: 2vw;
  height: 2vw;
  border: 0.4vw solid currentColor;
  border-right-color: transparent;
}

.wrap-loading .caption {
  margin-left: 10px;
  display: inline;
  text-align: left;
  vertical-align: super;
}
</style>