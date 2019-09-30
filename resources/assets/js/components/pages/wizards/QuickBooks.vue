<template>
  <div class="wrap-wizard md-layout md-alignment-center-center">
    <stepper>
      <template slot="nav">
        <step-icon
          v-model="currentStep"
          :step-number="1"
          :lable="'Step 1'"
          :disabled="currentStep > 1"
        />
        <step-icon
          v-model="currentStep"
          :step-number="2"
          :lable="'Step 2'"
          :disabled="currentStep > 2"
        />
        <step-icon
          v-model="currentStep"
          :step-number="3"
          :lable="'Step 3'"
          :disabled="currentStep > 3"
        />
        <step-icon
          v-model="currentStep"
          :step-number="4"
          :lable="'Step 4'"
          :disabled="currentStep > 4"
        />
      </template>
      <template slot="body">
        <!-- step 0 -->
        <step-body v-model="currentStep" :step-number="1">
          <div class="mt-4">
            <h3>QuickBook Setup</h3>
            <p class="text-justify">
              Teams are any easy way to group and manage employees. The schedule or employee
              lists can be easily filtered to show just a single team. An employee can belong
              to one or many teams. Teams can be set up for different locations, states or
              front and back of house. Please create some teams now. New teams can always be
              added later.
            </p>
            <button
              type="button"
              class="btn btn-success"
              :disabled="isLoading"
              @click="connectToQuickbook"
            >{{ isLoading ? 'Processing, Please wait...' : 'Connect To QuickBook' }}</button>
          </div>
        </step-body>

        <!-- step 1 -->
        <step-body v-model="currentStep" :step-number="2">
          <div class="mt-4">
            <h3>Company Info</h3>
            <p class="text-justify">{{ companyinfo }}</p>
          </div>
        </step-body>
      </template>
    </stepper>
  </div>
</template>

<script>
import { api } from "../../../api/http";
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
      currentStep: 1,
      firstStep: 1,
      lastStep: 4,
      companyinfo: "",

      // auth
      authEndPoint: "https://appcenter.intuit.com/connect/oauth2",
      clientID: "ABVz6DhHFhZcfqM3rmeQNtSYyfEzquOzy5LXC94dqQkYaWUSR5",
      responseType: "code",
      scope:
        "com.intuit.quickbooks.accounting openid profile email phone address",
      redirectURI: "https://server.dev/wizard/quickbooks",
      csrf: "1234"
    };
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

    exchangeCodeForToken(code, companyId = null) {
      this.isLoading = true;
      api
        .get({
          url: `/api/quickbooks/token/${code}${
            companyId ? `/${companyId}` : ""
          }`,
          isJson: true,
          isAuth: true
        })
        .then(res => {
          console.log(res);
          this.currentStep++;

          api
            .get({
              url: "/api/quickbooks/companyinfo",
              isJson: true,
              isAuth: true
            })
            .then(res => {
              console.log(res);
              this.companyinfo = res;
            })
            .catch(error => {
              console.log(error);
            });
        })
        .catch(error => {
          console.log(error);
        })
        .finally(() => {
          this.isLoading = false;
        });
    },

    processStep() {
      //
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
}
</style>