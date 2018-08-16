<template>
    <nav-view 
    :scrollbar="true" 
    :loading="false"
    menu-title="" 
    drawer-title="Actions"
    padding="p-3">        
        <template slot="menu">

        </template>
        <template slot="drawer">

        </template>
        <template slot="content">

            <form-stepper v-model="step">
                <template slot="signs">
                    <step-sign v-model="step" :step-number="1" label="step 1"></step-sign>
                    <step-sign v-model="step" :step-number="2" label="step 2"></step-sign>
                    <step-sign v-model="step" :step-number="3" label="step 3"></step-sign>
                </template>
                <template slot="body">
                    <step-body v-model="step" :step-number="1">

                        <form>                            

                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col">
                                        <label class="col-form-lable">First Name</label>
                                        <input type="text" class="form-control" id="firstname">                                        
                                    </div>
                                    <div class="col">
                                        <label class="col-form-lable">Last Name</label>
                                        <input type="text" class="form-control" id="lastname">
                                    </div>                                   
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col">
                                        <label class="col-form-lable">Tel Number</label>
                                        <input type="tel" class="form-control" id="telephone">
                                    </div>
                                    <div class="col">
                                        <label class="col-form-lable">Mobile Number</label>
                                        <input type="tel" class="form-control" id="mobilephone">
                                    </div>                                    
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col">
                                        <label class="col-form-lable">Group</label>
                                        <select class="custom-select" id="group">
                                            <option value="User">User</option>
                                            <option value="Customer">Customer</option>                                
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label class="col-form-lable">Is a User</label>
                                        <div class="custom-control custom-radio custom-control">
                                            <input type="radio" id="isUser" name="isUser" value="1" class="custom-control-input" checked>
                                            <label class="custom-control-label" for="isUser">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control">
                                            <input type="radio" id="notUser" name="isUser" value="0" class="custom-control-input">
                                            <label class="custom-control-label" for="notUser">No</label>
                                        </div>                                        
                                    </div>                                    
                                </div>
                            </div>

                        </form>

                    </step-body>
                    <step-body v-model="step" :step-number="2"></step-body>
                    <step-body v-model="step" :step-number="3"></step-body>
                </template>
                <template slot="nav">
                    <button @click="(step - 1 > 0) ? step--:step" class="btn btn-primary float-left" type="button">Prev</button>                    
                    <button @click="(step + 1 < 4) ? step++:step" class="btn btn-primary float-right" type="button">Next</button>                    
                </template>
            </form-stepper>
            
        </template>
    </nav-view>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import NavView from "../../../framework/NavView.vue";
import Stepper from "../../../framework/BaseFormStepper/BaseFormStepper.vue";
import StepSign from "../../../framework/BaseFormStepper/BaseFormStepSign.vue";
import StepBody from "../../../framework/BaseFormStepper/BaseFormStepBody.vue";

export default {
  name: "ActivityList",

  components: {
    "nav-view": NavView,
    "form-stepper": Stepper,
    "step-sign": StepSign,
    "step-body": StepBody
  },

  data() {
    return {
      step: 1
    };
  }
};
</script>

<style scoped>
</style>
