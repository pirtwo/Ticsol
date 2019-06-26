<template>
  <app-main
    :scrollbar="true"
    :loading="isLoading"
    padding="p-5"
  >
    <template slot="toolbar">
      <ts-pagination
        v-model="pager"
        :page-count="pager.pageCount"
        @input="changePage"
      />
    </template>

    <template slot="drawer">
      <ul class="v-menu">
        <li class="menu-title">
          Actions
        </li>
        <li>
          <button
            class="btn"
            @click="sendModal = true"
          >
            New
          </button>
        </li>
        <li>
          <button class="btn">
            Cancel
          </button>
        </li>
        <li class="menu-title">
          Links
        </li>
      </ul>
    </template>

    <template slot="content">
      <ul class="comment-list">
        <li
          v-for="parent in comments"
          :key="parent.id"
        >
          <ts-comment
            name="VbComment"
            @reply="onReply"
            :id="parent.id"
            :username="parent.creator.name"
            :avatar="parent.creator.meta.avatar"
            :body="parent.body"
            :date="getLocalDateTime(parent.created_at)"
            :has-reply="true"
          />
          <ul
            class="reply-list"
            v-if="parent.reply_count > 0"
          >
            <li
              v-for="child in parent.childs"
              :key="child.id"
            >
              <ts-comment
                name="VbComment"
                :id="child.id"
                :username="child.creator.name"
                :avatar="child.creator.meta.avatar"
                :body="child.body"
                :date="getLocalDateTime(child.created_at)"
                :has-reply="false"
              />
            </li>
          </ul>
        </li>
      </ul>
      <!-- Send Modal -->
      <ts-modal
        :show.sync="sendModal"
        @hidden="cancelModal"
      >
        <template slot="header">
          <h5 class="modal-title">
            <span v-if="parentId">
              Reply to
              <b>{{ getCreatorName(parentId) }}</b>
            </span>
            <span v-else>Send Comment</span>
          </h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </template>
        <editor
          v-model="comment"
          :init="{height: 300, menubar: false, statusbar: false}"
          api-key="he51k5qf4qe8668k9rgkie9c13j01h43fh61m72chuvv93ip"
          plugins="bbcode code"
          toolbar="newdocument | undo redo | cut copy paste pastetext | selectall | code"
        />
        <template slot="footer">
          <button
            type="button"
            class="btn btn-primary"
            @click="sendComment"
          >
            Send
          </button>
          <button
            type="button"
            class="btn btn-primary"
            @click="cancelModal"
          >
            Cancel
          </button>
        </template>
      </ts-modal>
    </template>
  </app-main>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import moment from 'moment';
import PageMixin from "../../../../mixins/page-mixin.js";
import Editor from "@tinymce/tinymce-vue";

export default {
  name: "CommentList",

  mixins: [PageMixin],

  components: {
    editor: Editor
  },

  props: ["entity", "id"],

  data() {
    return {
      comment: "",
      parentId: null,
      sendModal: false,
      pager: {
        page: 1,
        perPage: 10,
        pageCount: 10
      }
    };
  },

  computed: {
    ...mapGetters({
      getList: "resource/getList"
    }),

    comments: function() {
      return this.getList("comment");
    }
  },

  mounted() {
    this.startLoading();
    $(document).on("focusin", function(e) {
      if ($(e.target).closest(".tox-dialog").length) {
        e.stopImmediatePropagation();
      }
    });
    this.loadComments()
      .then(respond => {
        this.pager.pageCount = respond.last_page;
        this.stopLoading();
      })
      .catch(error => {
        this.stopLoading();
        this.showMessage(error.message, "danger");
      });
  },

  methods: {
    ...mapActions({
      fetchList: "resource/list",
      create: "resource/create"
    }),

    changePage() {
      this.startLoading();
      this.loadComments()
        .then(respond => {
          this.pager.pageCount = respond.last_page;
          this.stopLoading();
        })
        .catch(error => {
          this.stopLoading();
          this.showMessage(error.message, "danger");
        });
    },

    loadComments() {
      return this.fetchList({
        resource: "comment",
        query: {
          id: this.id,
          entity: this.entity,
          page: this.pager.page,
          perPage: this.pager.perPage
        }
      });
    },

    getCreatorName(commentId) {
      if (!commentId) return "";
      return this.comments.find(item => item.id === commentId).creator.name;
    },

    getLocalDateTime(date){
      let utcDate = moment.utc(date).toDate();
      return moment(utcDate).local().fromNow();
    },

    onReply(parentId) {
      this.sendModal = true;
      this.parentId = parentId;
    },

    cancelModal() {
      this.comment = "";
      this.parentId = null;
      this.sendModal = false;
    },

    sendComment() {

      let comment = {
        entity: this.entity,
        parent_id: this.parentId,
        body: this.comment,
        [`${this.entity}_id`]: this.id
      };

      this.parentId = null;
      this.sendModal = false;
      this.create({ resource: "comment", data: comment })
        .then(data => {
          this.showMessage("Your comment submited successfuly.", "success");
        })
        .catch(error => {
          this.showMessage(error.message, "danger");
        });
    }
  }
};
</script>

<style scoped>
.comment-list {
  list-style: none;
  margin: 0px;
  padding: 0px;
}

.reply-list {
  list-style: none;
  margin-left: 50px;
  padding-left: 30px;
  border-left: 1px solid #0000001a;
}
</style>
