<template>
  <div>
    <nav
      class="navbar header has-shadow is-primary"
      role="navigation"
      aria-label="main navigation"
    >
      <div class="navbar-brand">
        <a
          class="navbar-item"
          href="/"
        >
<!--          <img-->
<!--            src="~assets/buefy.png"-->
<!--            alt="Buefy"-->
<!--            height="28"-->
<!--          >-->
          URLShortener
        </a>

        <div class="navbar-burger">
          <span />
          <span />
          <span />
        </div>
      </div>

      <div class="navbar-end">
        <div class="navbar-item">
          <div class="buttons" v-if='$auth.loggedIn'>
            <a href="#" @click.prevent="logout" class="button is-light">
              Logout
            </a>
          </div>
          <div class="buttons" v-else>
            <NuxtLink to="/register" class="button is-primary">
              <strong>Register</strong>
            </NuxtLink>
            <NuxtLink to="/login" class="button is-light">
              <strong>Login</strong>
            </NuxtLink>
          </div>
        </div>
      </div>
    </nav>

    <section class="main-content columns">
<!--      <aside class="column is-2 section">-->
<!--        <p class="menu-label is-hidden-touch">-->
<!--          General-->
<!--        </p>-->
<!--        <ul class="menu-list">-->
<!--          <li-->
<!--            v-for="(item, key) of items"-->
<!--            :key="key"-->
<!--          >-->
<!--            <NuxtLink-->
<!--              :to="item.to"-->
<!--              exact-active-class="is-active"-->
<!--            >-->
<!--              <b-icon :icon="item.icon" /> {{ item.title }}-->
<!--            </NuxtLink>-->
<!--          </li>-->
<!--        </ul>-->
<!--      </aside>-->

      <div class="container column is-12">
        <Nuxt />
      </div>
    </section>
  </div>
</template>

<script>
export default {
  data () {
    return {
      items: [
        {
          title: 'Home',
          icon: 'home',
          to: { name: 'index' }
        },
        {
          title: 'Inspire',
          icon: 'lightbulb',
          to: { name: 'inspire' }
        }
      ]
    }
  },
  methods: {
    async logout() {
      await this.$auth.logout()

      this.$router.push('/login')
    }
  }
}
</script>
