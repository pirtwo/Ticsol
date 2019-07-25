<template>
  <div
    :id="id"
    class="ts-comment d-flex"
  >
    <img
      class="ts-comment__avatar rounded border"
      :src="avatar"
      :alt="`${username} avatar`"
    >
    <div class="ts-comment__body d-flex flex-column flex-grow-1">
      <div class="d-flex align-items-baseline">
        <div class="ts-comment__name">
          {{ username }}
        </div>
        <div class="ts-comment__date">
          created: {{ createdAt }}
        </div>
        <div
          v-if="updatedAt"
          class="ts-comment__date ml-2"
        >
          edited: {{ updatedAt }}
        </div>
        <div class="ml-auto">
          <button
            v-if="hasReply"
            type="button"
            class="btn btn-sm btn-link"
            @click="onReply"
          >
            reply
          </button>
          <button
            v-if="hasEdit"
            type="button"
            class="btn btn-sm btn-link"
            @click="onEdit"
          >
            edit
          </button>
          <button
            v-if="hasDelete"
            type="button"
            class="btn btn-sm btn-link"
            @click="onDelete"
          >
            delete
          </button>
        </div>
      </div>
      <div class>
        {{ body }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "TsComment",

  props: {
    id: {
      type: Number,
      default: 0
    },
    username: {
      type: String,
      default: ""
    },
    avatar: {
      type: String,
      default: ""
    },
    body: {
      type: String,
      default: ""
    },
    createdAt: {
      type: String,
      default: ""
    },
    updatedAt: {
      type: String,
      default: ""
    },
    hasReply: {
      type: Boolean,
      default: true
    },
    hasEdit: {
      type: Boolean,
      default: true
    },
    hasDelete: {
      type: Boolean,
      default: true
    }
  },

  data() {
    return {};
  },

  created() {},

  mounted() {},

  methods: {
    onReply(e) {
      console.log(this.id);
      this.$emit("reply", this.id);
    },

    onEdit(e) {
      console.log(this.id);
      this.$emit("edit", { id: this.id, body: this.body });
    },

    onDelete(e) {
      console.log(this.id);
      this.$emit("delete", this.id);
    }
  }
};
</script>

<style scoped>
.ts-comment {
  margin-bottom: 10px;
}

.ts-comment__name {
  font-size: 12px;
  font-weight: bold;
}

.ts-comment__avatar {
  width: 55px;
  height: 55px;
  margin: 20px;
}

.ts-comment__date {
  font-size: 10px;
  margin-left: 7px;
}

.ts-comment__body {
  border: 1px solid #0000001a;
  background-color: #ffffffe6;
  border-radius: 5px;
  padding: 10px;
}
</style>