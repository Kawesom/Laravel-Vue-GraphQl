<script setup>
import { Head, Link } from '@inertiajs/vue3';
import axios, { Axios } from 'axios';
import { onMounted, ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const posts = ref([]);

defineProps([
    //Rposts: Array,
])

onMounted(() => {
    axios('http://localhost:8000/graphql', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        data: {
            query: `
              query {
                posts {
                    data {
                        id
                        title
                        body
                        created_at
                        updated_at
                        }
                    }
                }
            `
        }
    })
    //.then(res => res.json()) in axios responses are already in json so no need to convert
    .then((result) => {
        console.log(result.data.data.posts.data)
        posts.value = result.data.data.posts.data
    })
});

function handleMutation() {
    axios('http://localhost:8000/graphql', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        data: {
            query: `
              mutation CreatePostResolver {
                    createPostResolver(user_id: "10", title: "Hello Vue", body: "Vue Content") {
                            id
                            title
                            body
                            created_at
                            updated_at
                            }
                        }
            `
        }
    })
    //.then(res => res.json()) in axios responses are already in json so no need to convert
    .then((result) => {
        //console.log(result.data.data.posts.data)
        //posts.value = result.data.data.posts.data
        alert('Post was created')
    })
}


/* fetch Api
onMounted(() => {
  fetch('http://localhost:8000/graphql', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      query: `
        query {
                posts {
                    data {
                        id
                        title
                        body
                        created_at
                        updated_at
                        }
                    }
                }
      `,
    }),
  })
    .then(res => res.json())
    .then(result => {
      console.log(result)
      posts.value = result.data.posts.data
    })
})
*/


</script>

<template>
    <Head title="Welcome" />
    <div>
        <ul class="text-center m-4">
            <li v-for="post in posts" :key="post.id">
                <div>Title: {{ post.title }}</div>
                <div>Content: {{ post.body }}</div>
            </li>
        </ul>
        <PrimaryButton @click="handleMutation">Mutate</PrimaryButton>
    </div>
</template>
