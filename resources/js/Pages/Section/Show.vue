<template>
<app-layout>
  <div class="columns is-centered">
    <div class="column is-10-tablet is-8-desktop is-7-widescreen">
      <div class="breadcrumb-wrapper">
        <div>
          <inertia-link class="back-link mb-0 has-text-dark" :href="route('course.show', {'course': $parameters.course })">{{ course.title }}</inertia-link> &gt; <inertia-link class="back-link mb-0 has-text-dark" accesskey="":href="route('week.show', {'course': $parameters.course, 'week': $parameters.week })">{{ week.name }}</inertia-link>
        </div>
        <course-navigator :$parameters="$parameters" :course_id="$parameters.course"></course-navigator>
      </div>
      <div class="box section-content">

        <h3 class="title is-2 has-text-weight-bold mt-4 mb-6">{{ section.title }}</h3>

        <div v-if="lesson.sections.length > 1" class="sections-box box p-4">
          <h3 class="has-text-weight-bold is-size-4 mt-0">Today’s lesson 🧑‍🏫</h3>
          <inertia-link v-for="section in lesson.sections" :key="section.id" :href="route('section.show', {'course': $parameters.course, 'week': $parameters.week, 'lesson': $parameters.lesson, 'section': section.id })" class="lesson-button mt-3 is-block p-2 is-bordered has-text-black is-outlined is-fullwidth">{{ section.title }}</inertia-link>
          <a class="mt-4 button is-small is-fullwidth" target="_blank" :href="route('lesson.print', {'course': $parameters.course, 'week': $parameters.week, 'lesson': $parameters.lesson })">🖨 Print this lesson</a>
        </div>

        <div v-if="section.order == 1 && lesson.instructions && lesson.instructions.length && lesson.instructions != '<p></p>'">
          <h3 class="has-text-weight-bold is-size-4">Instructions</h3>
          <div class="content" v-html="lesson.instructions"></div>
        </div>

        <div class="container content" :class="{'clear-both': section.order == 1 && lesson.instructions && lesson.instructions.length }">

          <div :is="dynamicComponent"></div>

          <!-- <div v-if="wordList.length">
            <div class="editor-js-block editor-js-block__paired-heading">
              <h2>Impare le parole 🔎</h2>
              <h3>Learn the words</h3>
            </div>
            <ul>
              <li v-for="word in wordList"><strong>{{word.word}}:</strong> {{ word.translation}}</li>
            </ul>
          </div> -->

        </div>

        <Chatroom class="negative-margin" v-if="section.is_chatroom" :replies="replies" :$user="$user" :comments="comments" :$parameters="$parameters"></Chatroom>

        <div class="section-footer container is-flex mt-5 pt-4">
          <inertia-link class="button is-medium " v-if="previousSectionID" :href="route('section.show', {'course': $parameters.course, 'week': $parameters.week, 'lesson': $parameters.lesson, 'section': previousSectionID })">Previous</inertia-link>
          <inertia-link class="button is-medium ml-a" v-if="nextSectionID" :href="route('section.show', {'course': $parameters.course, 'week': $parameters.week, 'lesson': $parameters.lesson, 'section': nextSectionID })">Next</inertia-link>
        </div>

      </div>
    </div>
  </div>
</app-layout>
</template>

<script>
import Chatroom from '@/components/Chatroom'

export default {
  props: ['blocks_prerendered', 'comments', 'replies', 'course', 'week', 'lesson', 'section', '$parameters', '$user'],

  components: {
    Chatroom,
  },

  data() {
    return {
    }
  },

  mounted() {

  },

  computed: {

  	dynamicComponent() {
    	return {
        template: `<div>${this.blocks_prerendered}</div>`
      }
    },

    // wordList() {
    //   var temp = document.createElement('template');
    //   temp.innerHTML = this.blocks_prerendered;
    //   let domWords = temp.content.querySelectorAll('mark[data-translation]');
    //   let arrayWords = [];
    //   domWords.forEach(node => {
    //     arrayWords.push({
    //       'word': node.innerHTML,
    //       'translation': node.dataset.translation
    //     });
    //   })
    //   return arrayWords;
    // },

    currentSectionIndex() {
      return this.lesson.sections.map((section) => { return section.id }).indexOf(this.section.id);
    },

    nextSectionID() {
      return this.lesson.sections[this.currentSectionIndex + 1]?.id;
    },

    previousSectionID() {
      return this.lesson.sections[this.currentSectionIndex - 1]?.id;
    }
  }
};
</script>

<style lang="scss">
@import "../../../sass/variables";

.section-content {
  padding: 3rem !important;

  @media(orientation: portrait) {
    padding: 2rem 1rem !important
  }
}

.sections-box {
  margin-top: -0.5em;
  margin-left: 2em;
  margin-right: 0em;
  margin-bottom: 3em;
  float: right;
  max-width: 16em;
  position: relative;
  z-index: 9;
}

.lesson-button {
  line-height: 1.3;
  border-color: $grey-lighter;
  border-radius: 4px;
}

.clear-both {
  clear: both;
}

.breadcrumb-wrapper {
  margin-bottom: 0.5rem;
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: space-between;

  @media screen and (orientation: portrait) {
    flex-direction: column;
  }
}

</style>
