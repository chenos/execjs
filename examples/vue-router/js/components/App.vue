<template>
  <div class="hello">
    <ul>
      <li><router-link to="/vue-router/" exact>Home</router-link></li>
      <li><router-link to="/vue-router/about">About</router-link></li>
    </ul>
    <router-view class="view"></router-view>
  </div>
</template>
