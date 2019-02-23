<template>
  <div 
    class="modal fade" 
    id="filterModal" 
    tabindex="-1" 
    role="dialog" 
    aria-labelledby="filter" 
    aria-hidden="true"
  >
    <div 
      class="modal-dialog modal-dialog-centered" 
      role="document"
    >
      <div class="modal-content">
        <div class="modal-header">
          <h5 
            class="modal-title" 
            id="filter"
          >
            Filter
          </h5>
          <button 
            type="button" 
            class="close" 
            data-dismiss="modal" 
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table table-sm table-responsive">
            <thead>
              <tr>
                <th scope="col" />
                <th scope="col">
                  <select 
                    v-model="col" 
                    class="baseFilter__select-column"
                  >
                    <option 
                      disabled 
                      value=""
                    >
                      Please select column
                    </option>
                    <option 
                      v-for="item in columns" 
                      :key="item.key" 
                      :value="item.key"
                    >
                      {{ item.value }}
                    </option>
                  </select>
                </th>
                <th scope="col">
                  <select 
                    v-model="opt" 
                    class="baseFilter__select-operator"
                  >
                    <option 
                      disabled 
                      value=""
                    >
                      Please select operator
                    </option>
                    <option 
                      v-for="item in operators" 
                      :key="item.key" 
                      :value="item.key"
                    >
                      {{ item.value }}
                    </option>
                  </select>
                </th>
                <th scope="col">
                  <input 
                    v-model="val" 
                    class="baseFilter__value-input" 
                    type="text"
                  >
                </th>
                <th>
                  <button 
                    type="button" 
                    @click="addHandler"
                  >
                    Add
                  </button>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr 
                v-for="(query, index) in filters" 
                :key="index"
              >
                <th scope="row">
                  {{ index + 1 }}
                </th>
                <td>{{ getColumnName(query.col) }}</td>
                <td>{{ getOperatorName(query.opt) }}</td>
                <td>{{ query.val }}</td>
                <td>
                  <button 
                    type="button" 
                    class="btn btn-link" 
                    @click="removeHandler(index)"
                  >
                    Remove
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button 
            type="button" 
            class="btn btn-outline-primary" 
            @click="applyHandler"
          >
            Apply
          </button>
          <button 
            type="button" 
            class="btn btn-outline-primary" 
            @click="filters = []"
          >
            Clear
          </button>
          <button 
            type="button" 
            class="btn btn-outline-primary" 
            data-dismiss="modal"
          >
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: "BaseFilter",

    props: {
      value: {
        type: Array,
        default: null
      },
      show: {
        type: Boolean,
        default: false
      },
      columns: {
        type: Array,
        default: null
      }
    },

    data() {
      return {
        col: "",
        opt: "",
        val: "",
        filters: [],
        operators: [
          { key: "cnt", value: "Contains" },
          { key: "ncnt", value: "Not Contains" },
          { key: "eq", value: "Equals" },
          { key: "neq", value: "Not Equals" },
          { key: "gt", value: "Greater Than" },
          { key: "lt", value: "Less Than" },
          { key: "gte", value: "Greater Than Or Equal" },
          { key: "lte", value: "Less Than Or Equal" },
          { key: "btw", value: "Between" },
          { key: "nbtw", value: "Not Btween" },
          { key: "in", value: "In" }
          //{ key: "orderby", value: "OrderBy" }
        ]
      };
    },

    watch: {
      show: function (value) {
        if (value) this.showModal();
        else this.hideModal();
      }
    },

    computed: {},

    created() { },

    mounted() {
      $("#filterModal").on("show.bs.modal", this.onShow);
      $("#filterModal").on("hide.bs.modal", this.onHide);
    },

    destroyed() { },

    methods: {
      showModal() {
        $("#filterModal").modal("show");
      },

      hideModal() {
        $("#filterModal").modal("hide");
      },

      onShow() {
        this.$emit("update:show", true);
      },

      onHide() {
        this.$emit("update:show", false);
      },

      getColumnName(key) {
        return this.columns.find(item => item.key === key).value;
      },

      getOperatorName(key) {
        return this.operators.find(item => item.key === key).value;
      },

      addHandler() {
        if (this.col === "" || this.opt === "" || this.val === "") return;
        this.filters.push({
          opt: this.opt,
          col: this.col,
          val: this.val
        });

        this.col = "";
        this.opt = "";
        this.val = "";
      },

      removeHandler(index) {
        this.filters.splice(index, 1);
      },

      applyHandler() {
        this.$emit("input", [...this.filters]);
        this.$emit("apply");
        this.hideModal();
      }
    }
  };
</script>

<style scoped>
  .baseFilter__select-column {
    font-size: 15px;
    max-width: 100px;
  }

  .baseFilter__select-operator {
    font-size: 15px;
    max-width: 100px;
  }

  .baseFilter__value-input {
    font-size: 15px;
    max-width: 150px;
  }
</style>