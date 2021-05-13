<template>
  <div class="print-wrapper">
    <h3 class="subtitle is-4 has-text-weight-bold">{{ course.title }}</h3>
    <h3 class="title is-2">{{ lesson.title }}</h3>


    <div v-for="(currentSection, index) in lesson.sections" class="section-content">

      <div class="box" v-if="index == 0 && lesson.instructions && lesson.instructions.length && lesson.instructions != '<p></p>'">
        <h3 class="has-text-weight-bold is-size-4">Instructions</h3>
        <div class="content" v-html="lesson.instructions"></div>
      </div>

      <h3 class="title is-3 has-text-weight-bold mt-6 mb-3">{{ currentSection.title }}</h3>


      <div class="container content" :class="{'clear-both': currentSection.order == 1 && lesson.instructions && lesson.instructions.length }">

        <div :is="dynamicComponent(blocks_prerendered[index])"></div>

        <div v-if="wordList.length">
          <div class="editor-js-block editor-js-block__paired-heading">
            <h2>Impare le parole 🔎</h2>
            <h3>Learn the words</h3>
          </div>
          <ul>
            <li v-for="word in wordList"><strong v-html="word.word">:</strong> <span v-html="word.translation"></span></li>
          </ul>
        </div>

      </div>



    </div>
  </div>
</template>

<script>
import Chatroom from '@/components/Chatroom'

export default {
  props: ['blocks_prerendered', 'comments', 'replies', 'course', 'lesson', 'section'],

  components: {
    Chatroom,
  },

  data() {
    return {
    }
  },

  mounted() {
    window.print();
  },

  methods: {
    dynamicComponent(content) {
      return {
        template: `<div>${content}</div>`
      }
    },
  },

  computed: {

    wordList() {
      var temp = document.createElement('template');
      temp.innerHTML = this.blocks_prerendered;
      let domWords = temp.content.querySelectorAll('mark[data-translation]');
      let arrayWords = [];
      domWords.forEach(node => {
        arrayWords.push({
          'word': node.innerHTML,
          'translation': node.dataset.translation
        });
      })
      return arrayWords;
    },

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

.navbar {
  position: relative !important;
  margin-bottom: 3rem;
  background-color: transparent !important;

  .navbar-brand {

  }

  .navbar-menu,
  .navbar-burger {
    display: none;
  }
}

@media screen {
  .print-wrapper {
    max-width: 800px;
    margin: 6rem auto;
  }
}

@page {
  padding-top: 84px;
}

.beacon-container,
.footer {
  display: none !important;
}

html {
  background-color: #fff !important;
}

body {
  font-size: 12pt !important;
}



.print-wrapper {

  h1, h2, h3, h4, h5 {
    page-break-after: avoid;
  }

  table, figure {
    page-break-inside: avoid;
  }

  .editor-js-block__audio,
  .editor-js-block__video {
    display: none !important;
  }

  .content .editor-js-image img {
    width: auto;
    max-height: 300px;
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
}
</style>
