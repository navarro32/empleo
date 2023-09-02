import Vue from 'vue';
import VueApollo from 'vue-apollo';
import ApolloClient from 'apollo-client';
import { HttpLink } from 'apollo-link-http';
import { onError } from 'apollo-link-error';
// Notice the use of the link-error tool from Apollo

Vue.use(VueApollo);

const httpLink = new HttpLink({
    uri: '/graphql',
});

// we use a usefull error handling tool provided by Apollo in order to execute some code when errors occur.
const onErrorLink = onError(({ graphQLErrors, networkError }) => {
    // We log every GraphQL errors
    if (graphQLErrors) {
        graphQLErrors.map(({ message, locations, path }) =>
            console.log(
                `[GraphQL error]: Message: ${message}, Location: ${locations}, Path: ${path}`,
            ),
        );
    }
    // When a 401 error occur, we log-out the user.
    if (networkError) {
        if (networkError.statusCode === 401) {
            window.location.href = '/api/security/logout';
        }
    }
});

const apolloClient = new ApolloClient({
    link: onErrorLink.concat(httpLink)
});

export default new VueApollo({
    defaultClient: apolloClient,
});