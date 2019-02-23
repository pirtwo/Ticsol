<template>
  <nav-view 
    :scrollbar="true" 
    :loading="isLoading" 
    padding="p-5"
  >
    <template slot="toolbar">
      <vb-pagination 
        v-model="pager" 
        :page-count="pager.pageCount" 
        @input="changePage"
      />
    </template>

    <template slot="drawer" />

    <template slot="content">
      <h6>
        {{ this.parentId !== null ? 'Reply to:' : 'Send New Comment' }}
        <a 
          v-if="this.parentId !== null" 
          :href="`#${this.parentId}`"
        >{{ `@${this.getCreatorName(this.parentId)}` }}</a>
      </h6>      
      <textarea 
        style="margin-bottom:10px;"
        class="form-control"
        v-model="comment" 
        name="comment" 
        cols="30"
      />
      <button 
        class="btn btn-default"
        type="button" 
        @click="sendComment"
      >
        Send
      </button>
      <button 
        v-show="this.parentId !== null"
        class="btn btn-default"
        type="button" 
        @click="cancelReply"
      >
        Cancel
      </button>
      <ul class="comment-list">
        <li 
          v-for="parent in comments" 
          :key="parent.id"
        >
          <vb-comment 
            name="VbComment"
            @reply="onReply"
            :id="parent.id"
            :username="parent.creator.name"
            :avatar="parent.creator.meta.avatar"
            :body="parent.body"
            :date="parent.created_at"
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
              <vb-comment 
                name="VbComment"
                :id="child.id"
                :username="child.creator.name"
                :avatar="child.creator.meta.avatar"
                :body="child.body"
                :date="child.created_at"
                :has-reply="false"
              />
            </li>
          </ul>
        </li>
      </ul>      
    </template>
  </nav-view>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import PageMixin from '../../../../mixins/page-mixin.js';
import NavView from "../../../framework/NavView.vue";

export default {
  name: "CommentList",

  mixins:[PageMixin],

  components: {
    "nav-view": NavView
  },

  props: ["entity", "id"],

  data() {
    return {
      comment: "",        
      parentId: null,
      pager: {
        page: 1,
        perPage: 10,
        pageCount: 10
      },
    };
  },

  computed:{
    ...mapGetters({
      getList: "resource/getList"
    }),

    comments: function(){
      return this.getList("comment");
    },
  },

  mounted() {
    this.loadingStart();
    this.loadComments()
      .then(respond => {        
        this.pager.pageCount = respond.last_page;      
        this.loadingStop();
      })
      .catch(error => {
        this.loadingStop();
        this.showMessage(error.message, 'danger');
      });
  },

  methods: {
    ...mapActions({
      fetchList: "resource/list",
      create: "resource/create"
    }),

    changePage() {   
      this.loadingStart();
      this.loadComments()
        .then(respond => {          
          this.pager.pageCount = respond.last_page; 
          this.loadingStop();          
        })
        .catch(error => {
          this.loadingStop();
          this.showMessage(error.message, 'danger');
        });
    },

    loadComments() {   
      this.cancelReply();   
      return this.fetchList({
        resource: 'comment',
        query: {
          id: this.id,
          entity: this.entity,
          page: this.pager.page,
          perPage: this.pager.perPage
        }
      });
    },

    getCreatorName(commentId){
      return this.comments.find(item=>item.id === commentId).creator.name;
    },

    onReply(parentId){
      this.parentId = parentId;      
    },

    cancelReply(){
      this.parentId = null;
    },

    sendComment() {
      let form = this.entity == 'job' ? {
          entity: 'job',
          parent_id: this.parentId,
          body: this.comment,
          job_id: this.id,          
      }:{
          entity: 'request',
          parent_id: this.parentId,
          body: this.comment,          
          request_id: this.id
      };
      this.create({resource: "comment", data: form})
        .then(data => {
          this.showMessage('Your comment submited successfuly.', 'success');
        })
        .catch(error => {
          this.showMessage(error.message, 'danger');
        });
    }
  }
};
</script>

<style scoped>
.comment-list{
  list-style: none;
  margin: 0px;
  margin-top: 45px;
  padding: 0px;
}

.reply-list{
  list-style: none;
  margin-left: 50px;
  padding-left: 30px;
  border-left: 1px solid #0000001a;
}
</style>
