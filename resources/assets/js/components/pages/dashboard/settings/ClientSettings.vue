<template>
  <app-main
    :scrollbar="true"
    :loading="isLoading"
    padding="p-5"
  >
    <template slot="drawer">
      <ul class="v-menu">
        <li class="menu-title">
          Actions
        </li>

        <li>
          <button
            type="button"
            class="btn"
            @click="onSave"
          >
            Save
          </button>
        </li>

        <li>
          <button
            type="button"
            class="btn"
            @click="onCancel"
          >
            Cancel
          </button>
        </li>

        <li class="menu-title">
          Links
        </li>

        <li>
          <router-link
            tag="button"
            class="btn"
            :to="{ name: 'quickbooskWizard' }"
          >
            Setup Quickbooks
          </router-link>
        </li>
      </ul>
    </template>
    <template slot="content">
      <!-- user settings -->
      <form>
        <ts-section
          class="mb-3"
          title="General"
        >
          <!-- business hours -->
          <div class="form-group">
            <div class="form-row">
              <label class="col-md-2 col-form-lable">
                Business Hours
                <i class="field-required">*</i>
              </label>
              <div class="col-md-10">
                <div
                  v-for="(item, index) in $v.settings.businessHours.$each.$iter"
                  :key="index"
                  class="form-row mt-1"
                >
                  <!-- day label -->
                  <div class="col-md-2">
                    {{ getDayName(item.day.$model) }}
                  </div>

                  <!-- isopen -->
                  <div class="col-md-2">
                    <div class="custom-control custom-checkbox">
                      <input
                        v-model="item.isopen.$model"
                        type="checkbox"
                        :id="`isopen-${index}`"
                        class="custom-control-input"
                      >
                      <label
                        :for="`isopen-${index}`"
                        class="custom-control-label"
                      >Open</label>
                    </div>
                  </div>

                  <!-- start -->
                  <div class="col mr-md-2">
                    <div class="form-row">
                      <input
                        v-model="item.start.$model"
                        type="time"
                        :class="[{'is-invalid' : item.start.$error } ,'form-control']"
                        :disabled="!item.isopen.$model"
                      >
                      <div
                        class="invalid-feedback"
                        v-if="!item.start.required"
                      >
                        From is required.
                      </div>
                      <div
                        class="invalid-feedback"
                        v-if="!item.start.before"
                      >
                        From must be before end.
                      </div>
                    </div>
                  </div>

                  <!-- end -->
                  <div class="col ml-md-2">
                    <div class="form-row">
                      <input
                        v-model="item.end.$model"
                        type="time"
                        :class="[{'is-invalid' : item.end.$error } ,'form-control']"
                        :disabled="!item.isopen.$model"
                      >
                      <div
                        class="invalid-feedback"
                        v-if="!item.end.required"
                      >
                        Till is required.
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- schedule view -->
          <div class="form-group mt-4">
            <div class="form-row">
              <label class="col-sm-2 col-form-lable">
                Schedule View
                <i class="field-required">*</i>
              </label>
              <div class="col-sm-10">
                <div class="custom-control custom-radio custom-control-inline">
                  <input
                    v-model="$v.settings.scheduleView.$model"
                    type="radio"
                    id="view1"
                    name="views"
                    value="user"
                    :class="[{'invalid' : $v.settings.scheduleView.$error } ,'custom-control-input']"
                  >
                  <label
                    class="custom-control-label"
                    for="view1"
                  >Employee</label>
                  <div
                    class="invalid-feedback"
                    v-if="!$v.settings.scheduleView.required"
                  >
                    Schedule view is required.
                  </div>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input
                    v-model="$v.settings.scheduleView.$model"
                    type="radio"
                    id="view2"
                    name="views"
                    value="job"
                    class="custom-control-input"
                  >
                  <label
                    class="custom-control-label"
                    for="view2"
                  >Jobs</label>
                </div>
              </div>
            </div>
          </div>

          <!-- schedule range -->
          <div class="form-group">
            <div class="form-row">
              <label class="col-sm-2 col-form-lable">
                Schedule Range
                <i class="field-required">*</i>
              </label>
              <div class="col-sm-10">
                <div class="custom-control custom-radio custom-control-inline">
                  <input
                    v-model="$v.settings.scheduleRange.$model"
                    type="radio"
                    id="range1"
                    name="ranges"
                    value="week"
                    :class="[{'invalid' : $v.settings.scheduleRange.$error } ,'custom-control-input']"
                  >
                  <label
                    class="custom-control-label"
                    for="range1"
                  >Week</label>
                  <div
                    class="invalid-feedback"
                    v-if="!$v.settings.scheduleRange.required"
                  >
                    Schedule range is required.
                  </div>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input
                    v-model="$v.settings.scheduleRange.$model"
                    type="radio"
                    id="range2"
                    name="ranges"
                    value="month"
                    class="custom-control-input"
                  >
                  <label
                    class="custom-control-label"
                    for="range2"
                  >Month</label>
                </div>
              </div>
            </div>
          </div>
        </ts-section>

        <ts-section
          class="mb-3"
          title="Billing"
        >
          <!-- hourly billing -->
          <div class="form-group">
            <div class="form-row">
              <label class="col-sm-12 col-form-lable">
                Hourly Billing
                <i class="field-required">*</i>
              </label>
              <div class="col-md-12">
                <div
                  :class="[{ 'is-invalid' : $v.billingSettings.hoursRound.$error || $v.billingSettings.hoursInterval.$error }, 'input-group']"
                >
                  <div class="input-group-prepend">
                    <label
                      class="input-group-text"
                      for="hoursRound"
                    >
                      Round Hours
                      <i class="field-required">*</i>
                    </label>
                  </div>
                  <!-- round -->
                  <select
                    v-model="$v.billingSettings.hoursRound.$model"
                    :class="[{'is-invalid' : $v.billingSettings.hoursRound.$error } ,'custom-select']"
                    id="hoursRound"
                    name="hoursRound"
                  >
                    <option
                      selected
                      disabled
                    >
                      Choose Rounding...
                    </option>
                    <option value="up">
                      Up
                    </option>
                    <option value="nearest">
                      To Nearest
                    </option>
                  </select>

                  <!-- interval -->
                  <select
                    v-model="$v.billingSettings.hoursInterval.$model"
                    :class="[{'is-invalid' : $v.billingSettings.hoursInterval.$error } ,'custom-select']"
                    id="hoursInterval"
                    name="hoursInterval"
                  >
                    <option
                      selected
                      disabled
                    >
                      Choose interval...
                    </option>
                    <option value="6">
                      6
                    </option>
                    <option value="15">
                      15
                    </option>
                    <option value="30">
                      30
                    </option>
                    <option value="60">
                      60
                    </option>
                  </select>
                  <div class="input-group-append">
                    <label
                      class="input-group-text"
                      for="hoursInterval"
                    >Minutes</label>
                  </div>
                </div>
                <div
                  class="invalid-feedback"
                  v-if="!$v.billingSettings.hoursRound.required"
                >
                  Round Hours is required.
                </div>
                <div
                  class="invalid-feedback"
                  v-if="!$v.billingSettings.hoursInterval.required"
                >
                  Interval is required.
                </div>
              </div>
            </div>
          </div>

          <!-- daily billing -->
          <div class="form-group">
            <div class="form-row">
              <label class="col-sm-12 col-form-lable">
                Daily Billing
                <i class="field-required">*</i>
              </label>
              <div class="col-sm-12">
                <div
                  :class="[{ 'is-invalid' : $v.billingSettings.daysRound.$error || $v.billingSettings.daysInterval.$error || $v.billingSettings.hoursInDay.$error }, 'input-group']"
                >
                  <!-- round -->
                  <div class="input-group-prepend">
                    <label
                      class="input-group-text"
                      for="daysRound"
                    >Round Days</label>
                  </div>
                  <select
                    v-model="$v.billingSettings.daysRound.$model"
                    :class="[{ 'is-invalid' : $v.billingSettings.daysRound.$error }, 'custom-select']"
                    id="daysRound"
                    name="daysRound"
                  >
                    <option
                      selected
                      disabled
                    >
                      Choose Rounding...
                    </option>
                    <option value="up">
                      Up
                    </option>
                    <option value="nearest">
                      To Nearest
                    </option>
                  </select>

                  <!-- interval -->
                  <select
                    v-model="$v.billingSettings.daysInterval.$model"
                    :class="[{ 'is-invalid' : $v.billingSettings.daysInterval.$error }, 'custom-select']"
                    id="daysInterval"
                    name="daysInterval"
                  >
                    <option
                      selected
                      disabled
                    >
                      Choose interval...
                    </option>
                    <option value="0.25">
                      0.25
                    </option>
                    <option value="0.5">
                      0.5
                    </option>
                    <option value="1">
                      1
                    </option>
                  </select>
                  <div class="input-group-append">
                    <label
                      class="input-group-text"
                      for="daysInterval"
                    >Day</label>
                  </div>

                  <!-- hours in day -->
                  <div class="input-group-prepend">
                    <span class="input-group-text">1 Day Equals</span>
                  </div>
                  <input
                    v-model="$v.billingSettings.hoursInDay.$model"
                    type="text"
                    :class="[{ 'is-invalid' : $v.billingSettings.hoursInDay.$error }, 'form-control']"
                  >
                  <div class="input-group-append">
                    <span class="input-group-text">Hours</span>
                  </div>
                </div>
                <div
                  class="invalid-feedback"
                  v-if="!$v.billingSettings.daysRound.required"
                >
                  Round Days is required.
                </div>
                <div
                  class="invalid-feedback"
                  v-if="!$v.billingSettings.daysInterval.required"
                >
                  Days Interval is required.
                </div>
                <div
                  class="invalid-feedback"
                  v-if="!$v.billingSettings.hoursInDay.required"
                >
                  Hours In Day is required.
                </div>
                <div
                  class="invalid-feedback"
                  v-if="!$v.billingSettings.hoursInDay.between"
                >
                  Hours In Day must be between 1 and 24.
                </div>
                <div
                  class="invalid-feedback"
                  v-if="!$v.billingSettings.hoursInDay.decimal"
                >
                  Hours In Day must be a number.
                </div>
              </div>
            </div>
          </div>

          <fieldset :disabled="integration !== 'qbs'">
            <!-- revenue accounts -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-md-6 col-form-lable">
                  Revenue Accounts
                  <i
                    v-show="integration === 'qbs'"
                    class="field-required"
                  >*</i>
                </label>
                <div class="col-md-6">
                  <ts-chipsbox
                    v-model="$v.billingSettings.revenueAccounts.$model"
                    :data="revenueAccList"
                    :multi="true"
                    :disabled="integration !== 'qbs'"
                    :class="[{ 'is-invalid' : $v.billingSettings.revenueAccounts.$error }]"
                    id="revenueAccounts"
                    name="revenueAccounts"
                    placeholder="Select revenue accounts"
                    search-placeholder="search here..."
                  />
                  <div
                    class="invalid-feedback"
                    v-if="!$v.billingSettings.revenueAccounts.required"
                  >
                    Revenue Accounts are required.
                  </div>
                </div>
              </div>
            </div>

            <!-- allow prepiad jobs -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-md-6 col-form-lable">
                  Allow Prepiad Jobs?
                  <i
                    v-show="integration === 'qbs'"
                    class="field-required"
                  >*</i>
                </label>
                <div class="col-md-6">
                  <div class="custom-control custom-switch">
                    <input
                      type="checkbox"
                      v-model="$v.billingSettings.allowPrepaidJobs.$model"
                      class="custom-control-input"
                      id="allowPrepaidJobs"
                    >
                    <label
                      class="custom-control-label"
                      for="allowPrepaidJobs"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- income in advance accounts -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-md-6 col-form-lable">Income In Advance Account</label>
                <div class="col-md-6">
                  <ts-chipsbox
                    v-model="$v.billingSettings.incomeInAdvAccount.$model"
                    :disabled="!billingSettings.allowPrepaidJobs"
                    :data="incomeInAdvAccList"
                    :class="[{ 'is-invalid' : $v.billingSettings.incomeInAdvAccount.$error }]"
                    id="incomeInAdvanceAccount"
                    name="incomeInAdvanceAccount"
                    placeholder="Select income in advance account"
                    search-placeholder="search here..."
                  />
                  <div
                    class="invalid-feedback"
                    v-if="!$v.billingSettings.incomeInAdvAccount.required"
                  >
                    Income In Advance Account is required.
                  </div>
                </div>
              </div>
            </div>
          </fieldset>
        </ts-section>

        <ts-section
          class="mb-3"
          title="Billing Defaults"
        >
          <!-- payment type -->
          <div class="form-group">
            <div class="form-row">
              <label class="col-md-6 col-form-lable">
                Prepaid or Paid in arrears?
                <i class="field-required">*</i>
              </label>
              <div class="col-md-6">
                <div class="custom-control custom-radio custom-control-inline">
                  <input
                    v-model="$v.billingDefaults.paymentType.$model"
                    type="radio"
                    id="prepaid"
                    name="paymentType"
                    value="prepaid"
                    :class="[{ 'is-invalid' : $v.billingDefaults.paymentType.$error }, 'custom-control-input']"
                  >
                  <label
                    class="custom-control-label"
                    for="prepaid"
                  >Prepaid</label>
                  <div
                    class="invalid-feedback"
                    v-if="!$v.billingDefaults.paymentType.required"
                  >
                    Payment Type is required.
                  </div>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input
                    v-model="$v.billingDefaults.paymentType.$model"
                    type="radio"
                    id="inArrears"
                    name="paymentType"
                    value="inArrears"
                    :class="[{ 'is-invalid' : $v.billingDefaults.paymentType.$error }, 'custom-control-input']"
                  >
                  <label
                    class="custom-control-label"
                    for="inArrears"
                  >In Arrears</label>
                </div>
              </div>
            </div>
          </div>

          <fieldset :disabled="billingDefaults.paymentType !== 'prepaid'">
            <!-- over billing -->
            <div class="form-group">
              <div class="form-row">
                <label class="col-md-6 col-form-lable">Allow Billing above total job value?</label>
                <div class="col-md-6">
                  <div class="custom-control custom-switch">
                    <input
                      v-model="$v.billingDefaults.allowOverbilling.$model"
                      type="checkbox"
                      :class="[{ 'is-invalid' : $v.billingDefaults.allowOverbilling.$error }, 'custom-control-input']"
                      id="overBilling"
                    >
                    <label
                      class="custom-control-label"
                      for="overBilling"
                    />
                    <div
                      class="invalid-feedback"
                      v-if="!$v.billingDefaults.paymentType.required"
                    >
                      Allow Overbilling is required.
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>

          <fieldset
            :disabled="billingDefaults.paymentType !== 'prepaid' || !billingDefaults.allowOverbilling"
          >
            <!-- job fallback rate -->
            <div class="form-group">
              <div class="form-row">
                <label
                  class="col-md-6 col-form-lable"
                >When billing above total job value apply same job rate or company default rate?</label>
                <div class="col-md-6">
                  <div class="custom-control custom-radio custom-control-inline">
                    <input
                      v-model="$v.billingDefaults.jobFallbackRate.$model"
                      value="sameRate"
                      type="radio"
                      id="sameRate"
                      name="jobFallbackRate"
                      :class="[{ 'is-invalid' : $v.billingDefaults.jobFallbackRate.$error }, 'custom-control-input']"
                    >
                    <label
                      class="custom-control-label"
                      for="sameRate"
                    >Same Rate</label>
                    <div
                      class="invalid-feedback"
                      v-if="!$v.billingDefaults.jobFallbackRate.required"
                    >
                      Fallback Rate is required.
                    </div>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                    <input
                      v-model="$v.billingDefaults.jobFallbackRate.$model"
                      value="companyDefault"
                      type="radio"
                      id="companyDefault"
                      name="jobFallbackRate"
                      :class="[{ 'is-invalid' : $v.billingDefaults.jobFallbackRate.$error }, 'custom-control-input']"
                    >
                    <label
                      class="custom-control-label"
                      for="companyDefault"
                    >Company Default</label>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>

          <!-- unit type -->
          <div class="form-group">
            <div class="form-row">
              <label class="col-md-6 col-form-lable">
                Billing Unit Type
                <i class="field-required">*</i>
              </label>
              <div class="col-md-6">
                <select
                  v-model="$v.billingDefaults.billingUnitType.$model"
                  name="unitType"
                  id="unitType"
                  :class="[{ 'is-invalid' : $v.billingDefaults.billingUnitType.$error }, 'custom-select']"
                >
                  <option
                    selected
                    disabled
                  >
                    Choose Unit Type...
                  </option>
                  <option value="minutes">
                    Minutes
                  </option>
                  <option value="days">
                    Days
                  </option>
                </select>
                <div
                  class="invalid-feedback"
                  v-if="!$v.billingDefaults.billingUnitType.required"
                >
                  Unit Type is required.
                </div>
              </div>
            </div>
          </div>

          <!-- revenue account -->
          <div class="form-group">
            <div class="form-row">
              <label class="col-md-6 col-form-lable">
                Default Revenue Account
                <i
                  v-show="integration === 'qbs'"
                  class="field-required"
                >*</i>
              </label>
              <div class="col-md-6">
                <ts-chipsbox
                  v-model="$v.billingDefaults.revenueAccount.$model"
                  :data="revenueAccList"
                  :disabled="integration !== 'qbs'"
                  :class="[{ 'is-invalid' : $v.billingDefaults.revenueAccount.$error }]"
                  id="revenueAccounts"
                  name="revenueAccounts"
                  placeholder="Select default revenue account"
                  search-placeholder="search here..."
                />
                <div
                  class="invalid-feedback"
                  v-if="!$v.billingDefaults.revenueAccount.required"
                >
                  Default Revenue Account is required.
                </div>
              </div>
            </div>
          </div>

          <!-- company rate -->
          <div class="form-group">
            <div class="form-row">
              <label class="col-md-6 col-form-lable">
                Company Default Rate
                <i class="field-required">*</i>
              </label>
              <div class="col-md-6">
                <input
                  v-model="$v.billingDefaults.companyRate.$model"
                  type="text"
                  name="companyRate"
                  id="companyRate"
                  :class="[{ 'is-invalid' : $v.billingDefaults.companyRate.$error }, 'form-control']"
                >
                <div
                  class="invalid-feedback"
                  v-if="!$v.billingDefaults.companyRate.required"
                >
                  Default Rate is required.
                </div>
              </div>
            </div>
          </div>
        </ts-section>

        <ts-section
          class="mb-5"
          title="Reimbirsment"
        >
          <!-- rate and measure -->
          <div class="form-group">
            <div class="form-row">
              <label class="col-md-12 col-form-lable">
                Travel Claims
                <i class="field-required">*</i>
              </label>
              <div class="col-md-12">
                <div
                  :class="[{ 'is-invalid' : $v.reimbSettings.rate.$error || $v.reimbSettings.measure.$error }, 'input-group']"
                >
                  <div class="input-group-prepend">
                    <label
                      class="input-group-text"
                      for="reimbRate"
                    >
                      Reimburse Car Travel At
                      <i class="field-required">*</i>
                    </label>
                  </div>
                  <input
                    v-model="$v.reimbSettings.rate.$model"
                    type="text"
                    name="reimbRate"
                    id="reimbRate"
                    :class="[{ 'is-invalid' : $v.reimbSettings.rate.$error }, 'form-control']"
                  >
                  <div class="input-group-prepend input-group-append">
                    <label
                      class="input-group-text"
                      for="reimbMeasure"
                    >
                      Cents Per
                      <i class="field-required">*</i>
                    </label>
                  </div>
                  <select
                    v-model="$v.reimbSettings.measure.$model"
                    :class="[{ 'is-invalid' : $v.reimbSettings.measure.$error }, 'custom-select']"
                    id="reimbMeasure"
                    name="reimbMeasure"
                  >
                    <option
                      selected
                      disabled
                    >
                      Choose Measure...
                    </option>
                    <option value="kilometres">
                      Kilometres
                    </option>
                    <option value="miles">
                      Miles
                    </option>
                  </select>
                </div>
                <div
                  class="invalid-feedback"
                  v-if="!$v.reimbSettings.rate.required"
                >
                  Rate is required.
                </div>
                <div
                  class="invalid-feedback"
                  v-if="!$v.reimbSettings.rate.decimal"
                >
                  Rate must be a number.
                </div>
                <div
                  class="invalid-feedback"
                  v-if="!$v.reimbSettings.measure.required"
                >
                  Measure is required.
                </div>
              </div>
            </div>
          </div>

          <!-- reimb expence account -->
          <div class="form-group mt-3">
            <div class="form-row">
              <label class="col-md-3 col-form-lable">Expense Car Travel To</label>
              <div class="col-md-9">
                <ts-chipsbox
                  v-model="$v.reimbSettings.expenceAccount.$model"
                  :data="expenseAccList"
                  :disabled="integration !== 'qbs'"
                  id="reimbExpenceAccount"
                  name="reimbExpenceAccount"
                  placeholder="Select expence account"
                  search-placeholder="search here..."
                />
              </div>
            </div>
          </div>
        </ts-section>
      </form>
    </template>
  </app-main>
</template>

<script>
import * as QBs from "../../../../api/qbs-endpoints";
import { api } from "../../../../api/http";
import { mapGetters, mapActions } from "vuex";
import pageMixin from "../../../../mixins/page-mixin";
import {
  helpers,
  required,
  requiredIf,
  decimal,
  between
} from "vuelidate/lib/validators";

const before = prop =>
  helpers.withParams({ type: "before", prop: prop }, function(value, parentVm) {
    return helpers.ref("isopen", this, parentVm)
      ? value < helpers.ref(prop, this, parentVm)
      : true;
  });

export default {
  name: "ClientSettings",

  mixins: [pageMixin],

  data() {
    return {
      integration: "",

      // raw lists
      revenueAccounts: [],
      expenseAccounts: [],
      incomeInAdvAccounts: [],

      // wizard form data
      qbsAuth: false,
      xeroAuth: false,

      settings: {
        businessHours: [
          { day: 1, start: "00:00", end: "00:00", isopen: false },
          { day: 2, start: "00:00", end: "00:00", isopen: false },
          { day: 3, start: "00:00", end: "00:00", isopen: false },
          { day: 4, start: "00:00", end: "00:00", isopen: false },
          { day: 5, start: "00:00", end: "00:00", isopen: false },
          { day: 6, start: "00:00", end: "00:00", isopen: false },
          { day: 0, start: "00:00", end: "00:00", isopen: false }
        ],
        scheduleView: "",
        scheduleRange: ""
      },

      // billing
      billingSettings: {
        /**
         * could be: up, nearest.
         */
        hoursRound: "",

        /**
         * could be: 6, 15, 30, 60
         */
        hoursInterval: "",

        /**
         * could be: up, nearest.
         */
        daysRound: "",

        /**
         * could be: 0.25, 0.5, 1.
         */
        daysInterval: "",

        /**
         * could be between [1, 24].
         */
        hoursInDay: "",

        revenueAccounts: [],
        allowPrepaidJobs: false,
        incomeInAdvAccount: null
      },

      // billing defaults
      billingDefaults: {
        /**
         * could be: prepaid, inArrears
         */
        paymentType: "prepaid",

        /**
         * @type Boolean
         */
        allowOverbilling: false,

        /**
         * could be: sameRate, companyDefault
         */
        jobFallbackRate: "sameRate",

        /**
         * could be: minutes, days.
         */
        billingUnitType: "",

        /**
         * @type Object
         */
        revenueAccount: null,
        companyRate: ""
      },

      // reimbersment
      reimbSettings: {
        rate: "",

        /**
         * could be: kilometres, miles.
         */
        measure: "",
        expenceAccount: null
      }
    };
  },

  validations: {
    settings: {
      businessHours: {
        required,
        $each: {
          day: { required },
          start: { required, before: before("end") },
          end: { required },
          isopen: { required }
        }
      },
      scheduleView: { required },
      scheduleRange: { required }
    },

    billingSettings: {
      hoursRound: { required },
      hoursInterval: { required },
      daysRound: { required },
      daysInterval: { required },
      hoursInDay: { required, decimal, between: between(1, 24) },
      revenueAccounts: {
        required: requiredIf(function() {
          return this.integration === "qbs";
        })
      },
      allowPrepaidJobs: {},
      incomeInAdvAccount: {
        required: requiredIf(function() {
          return (
            this.integration === "qbs" && this.billingSettings.allowPrepaidJobs
          );
        })
      }
    },

    billingDefaults: {
      paymentType: { required },
      allowOverbilling: { required },
      jobFallbackRate: { required },
      billingUnitType: { required },
      revenueAccount: {
        required: requiredIf(function() {
          return this.integration === "qbs";
        })
      },
      companyRate: { required, decimal }
    },

    reimbSettings: {
      rate: { required, decimal },
      measure: { required },
      expenceAccount: {}
    }
  },

  computed: {
    ...mapGetters({
      clientId: "user/getClientId"
    }),

    revenueAccList: function() {
      return this.revenueAccounts.map(item => {
        return { key: item.Id, value: item.Name };
      });
    },

    incomeInAdvAccList: function() {
      return this.incomeInAdvAccounts.map(item => {
        return { key: item.Id, value: item.Name };
      });
    },

    expenseAccList: function() {
      return this.expenseAccounts.map(item => {
        return { key: item.Id, value: item.Name };
      });
    }
  },

  beforeRouteEnter(to, from, next) {
    next(vm => {
      vm.loadAssets();
    });
  },

  beforeRouteLeave(to, from, next) {
    this.clear("client");
    next();
  },

  methods: {
    ...mapActions({
      show: "resource/show",
      update: "resource/update",
      clear: "resource/clearResource"
    }),

    async loadAssets() {
      this.startLoading();
      let formData = null;
      let p1, p2;

      p1 = this.show({ resource: "client", id: this.clientId }).then(data => {
        formData = data;
      });

      p2 = this.checkQBsAuth();

      await Promise.all([p1, p2]);

      if (this.qbsAuth) await this.loadQBsAccounts();

      this.populateForm(formData);
      this.stopLoading();
    },

    populateForm(client) {
      let settings = client.meta;
      let billing = client.billing_settings;
      let billingDefaults = client.billing_defaults;
      let reimbSettings = client.reimbursement_settings;

      if (settings) {
        if (settings.business_hours) {
          for (let i = 0; i < settings.business_hours.length; i++) {
            this.settings.businessHours[i].isopen =
              settings.business_hours[i].isopen;
            this.settings.businessHours[i].start =
              settings.business_hours[i].start;
            this.settings.businessHours[i].end = settings.business_hours[i].end;
          }
        }

        this.settings.scheduleView = settings.schedule_view;
        this.settings.scheduleRange = settings.schedule_range;
      }

      if (billing) {
        this.billingSettings.hoursRound = billing.hours_round;
        this.billingSettings.hoursInterval = billing.hours_interval;
        this.billingSettings.daysRound = billing.days_round;
        this.billingSettings.daysInterval = billing.days_interval;
        this.billingSettings.hoursInDay = billing.hours_in_day;
        this.billingSettings.allowPrepaidJobs = billing.allow_prepaid_jobs;
        this.billingSettings.revenueAccounts = this.revenueAccList.filter(
          item => billing.revenue_accounts.find(acc => acc.Id === item.key)
        );
        this.billingSettings.incomeInAdvAccount = this.incomeInAdvAccList.find(
          item => item.key === billing.income_in_adv_account_id
        );
      }

      if (billingDefaults) {
        this.billingDefaults.paymentType = billingDefaults.payment_type;
        this.billingDefaults.allowOverbilling =
          billingDefaults.allow_over_billing;
        this.billingDefaults.jobFallbackRate =
          billingDefaults.job_fallback_rate;
        this.billingDefaults.billingUnitType = billingDefaults.unit_type;
        this.billingDefaults.companyRate = billingDefaults.company_rate;
        this.billingDefaults.revenueAccount = this.revenueAccList.find(
          item => item.key === billingDefaults.revenue_account_id
        );
      }

      if (reimbSettings) {
        this.reimbSettings.rate = reimbSettings.rate;
        this.reimbSettings.measure = reimbSettings.measure;
        this.reimbSettings.expenceAccount = this.expenseAccList.find(
          item => item.key === reimbSettings.expence_account_id
        );
      }
    },

    async checkQBsAuth() {
      return api.get({ url: "/api/quickbooks/hasvalidtoken" }).then(res => {
        this.qbsAuth = res.data.isTokenValid;
        this.integration = this.qbsAuth ? "qbs" : "";
      });
    },

    async loadQBsAccounts() {
      let p1 = api
        .get({ url: QBs.ACCOUNT, query: { classification: "Revenue" } })
        .then(res => {
          this.revenueAccounts = res.data ? res.data : [];
        });

      let p2 = api
        .get({
          url: QBs.ACCOUNT,
          query: { classification: "Expense" }
        })
        .then(res => {
          this.expenseAccounts = res.data ? res.data : [];
        });

      let p3 = api
        .get({
          url: QBs.ACCOUNT,
          query: { accountType: "Other Current Liability" }
        })
        .then(res => {
          this.incomeInAdvAccounts = res.data ? res.data : [];
        });

      return await Promise.all([p1, p2, p3]).catch(error => {
        this.showMessage(
          "Can't load Quickbooks accounts, please check your connection or setup Quickbooks connection again.",
          "danger"
        );
      });
    },

    getDayName(dayNum) {
      switch (dayNum) {
        case 0:
          return "Sunday";
        case 1:
          return "Monday";
        case 2:
          return "Tuesday";
        case 3:
          return "Wednesday";
        case 4:
          return "Thursday";
        case 5:
          return "Friday";
        case 6:
          return "Saturday";
        default:
          return "";
      }
    },

    onSave(e) {
      e.preventDefault();
      e.target.disable = true;

      this.$v.settings.$touch();
      this.$v.settings.businessHours.$touch();
      this.$v.billingSettings.$touch();
      this.$v.billingDefaults.$touch();
      this.$v.reimbSettings.$touch();
      if (
        this.$v.settings.$invalid ||
        this.$v.billingSettings.$invalid ||
        this.$v.billingDefaults.$invalid ||
        this.$v.reimbSettings.$invalid
      ) {
        e.target.disable = false;
        return;
      }

      // setup the req body
      let form = {};
      form.business_hours = this.settings.businessHours;
      form.schedule_view = this.settings.scheduleView;
      form.schedule_range = this.settings.scheduleRange;

      form.billing_hours_round = this.billingSettings.hoursRound;
      form.billing_hours_interval = this.billingSettings.hoursInterval;
      form.billing_days_round = this.billingSettings.daysRound;
      form.billing_days_interval = this.billingSettings.daysInterval;
      form.billing_hours_in_day = this.billingSettings.hoursInDay;
      form.billing_revenue_accounts = this.revenueAccounts.filter(item =>
        this.billingSettings.revenueAccounts.find(acc => acc.key === item.Id)
      );
      form.billing_allow_prepaid_jobs = this.billingSettings.allowPrepaidJobs;
      form.billing_income_in_adv_account_id = this.billingSettings
        .allowPrepaidJobs
        ? this.billingSettings.incomeInAdvAccount.key
        : null;

      form.billing_defaults_payment_type = this.billingDefaults.paymentType;
      form.billing_defaults_allow_over_billing = this.billingDefaults.allowOverbilling;
      form.billing_defaults_job_fallback_rate = this.billingDefaults.jobFallbackRate;
      form.billing_defaults_unit_type = this.billingDefaults.billingUnitType;
      form.billing_defaults_revenue_account_id = this.billingDefaults
        .revenueAccount
        ? this.billingDefaults.revenueAccount.key
        : null;
      form.billing_defaults_company_rate = this.billingDefaults.companyRate;

      form.reimbursement_rate = this.reimbSettings.rate;
      form.reimbursement_measure = this.reimbSettings.measure;
      form.reimbursement_expence_account_id = this.reimbSettings.expenceAccount
        ? this.reimbSettings.expenceAccount.key
        : null;

      this.update({ resource: "client", id: this.clientId, data: form })
        .then(data => {
          this.populateForm(data);
          this.showMessage(`Settings updated successfuly.`, "success");
        })
        .catch(error => {
          this.showMessage(error.message, "danger");
        })
        .finally(() => {
          e.target.disable = false;
        });
    },

    onCancel(e) {
      e.preventDefault();
      this.$router.go(-1);
    }
  }
};
</script>

<style scoped>
</style>
