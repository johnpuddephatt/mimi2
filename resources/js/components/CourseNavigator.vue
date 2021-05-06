<template>
<div>
  <b-button label="Course navigator" size="is-medium" type="is-primary" @click="isOpen = !isOpen" :icon-right="isOpen ? 'menu-up' : 'menu-down'" />
  <transition name="fade">
    <div @click="isOpen = false" v-if="isOpen" class="course-navigator-menu--mask">
      <div @click.stop aria-role="menu" class="menu course-navigator-menu has-background-light has-box-shadow p-4 pt-6">
        <div v-if="isLoaded">
          <h3 class="title is-5">{{ course.title }}</h3>
          <input v-model="search" @click.stop class="input" type="text" placeholder="Search...">
          <div v-for="week in filteredData" :key="week.id">
            <div v-if="week.live">
              <p class="has-text-weight-bold is-size-6 mt-4 mb-2">
                {{ week.name}}
              </p>
              <ul class="menu-list">
                <li v-for="lesson in week.lessons" :key="lesson.id" :class="{'is-current' : (lesson.id == $page.props.parameters.lesson) }">
                  <a class="menu-heading" @click="open = lesson.id" >{{ lesson.title }}</a>

                  <ul v-if="(lesson.id == open) || (lesson.id == $page.props.parameters.lesson) || search">
                    <li v-for="section in lesson.sections">
                      <inertia-link :class="{'is-active' : (lesson.id == $page.props.parameters.lesson && section.id == $page.props.parameters.section) }" :href="route('section.show', {course: course.id, week: week.number, lesson: lesson.id, section: section.id })">{{ section.title }}</inertia-link>
                    </li>
                    <li v-if="!lesson.sections.length" class="notification is-size-7">No sections in this lesson</li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <b-loading v-else />
      </div>
    </div>
  </transition>
</div>
</template>

<script>
export default {
  props: ['course_id'],

  data() {
    return {
      open: null,
      search: null,
      course: {},
      isOpen: false,
      isLoaded: false,
      errorLoading: false,
      filteredData: []
    }
  },

  computed: {

  },

  mounted() {},

  watch: {
    search() {
      this.filterData();
    },
    isOpen() {
      if (!this.isLoaded) {
        this.fetchData();
      }
    }
  },

  methods: {
    filterData() {
      this.filteredData = JSON.parse(JSON.stringify(this.course.weeks));

      if(this.search) {
        this.filteredData = this.filteredData.filter(week => {
          week.lessons = week.lessons.filter(lesson => {
            lesson.sections = lesson.sections.filter(section => {
              // Sections stay in if their name matches.
              return section.title.toLowerCase().includes(this.search.toLowerCase());
            });
            // lessons stay in if they have sections within OR if their own name matches.
            return lesson.sections.length || lesson.title.toLowerCase().includes(this.search.toLowerCase());
          })
          // Weeks stay in if they have anything under them. Their name isn't searched.
          return week.lessons.length ? true : false;
        })
      }
    },

    fetchData() {
      axios({
          method: 'get',
          url: route('course.map', {
            'course': this.course_id
          }),
          timeout: 15000
        })
        .then(response => {
          this.course = response.data;
          this.filteredData = JSON.parse(JSON.stringify(response.data.weeks));
          this.isLoaded = true;
        })
        .catch(error => {
          this.errorLoading = true;
          axios.post('/log', {
            'error': `COURSE MAP GET ERROR, ${ platform.description }, ${ JSON.stringify(error) }`
          });
        })
    }
  }
}
</script>

<style lang="scss">
@import "../../sass/variables";

.course-navigator-menu--mask {
  z-index: 998;
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  background-color: rgba(0,0,0,0.5  );
}

.course-navigator-menu {
  position: fixed;
  top: 0;
  bottom: 0;
  right: 0;
  z-index: 999;
  width: 350px;
  overflow-y: auto;
}

.is-current .menu-heading {
  color: darken($success, 0.2);
  font-weight: bold;
  background-color: transparentize($success, 0.85);
  cursor: default;
  &:hover {
    color: darken($success, 0.2);
    background-color: transparentize($success, 0.85);
  }
}
</style>
