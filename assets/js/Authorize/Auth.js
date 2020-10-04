import React from 'react';

const Auth = {
    authenticate(user) {
        sessionStorage.setItem('isAuthenticated', true);
        sessionStorage.setItem('userId', user.id);
        sessionStorage.setItem('firstName', user.firstName);
        sessionStorage.setItem('lastName', user.lastName);
        sessionStorage.setItem('email', user.email);
        sessionStorage.setItem('role', user.role);
    },

    signout() {
        sessionStorage.removeItem('isAuthenticated');
        sessionStorage.removeItem('userId');
        sessionStorage.removeItem('firstName');
        sessionStorage.removeItem('lastName');
        sessionStorage.removeItem('email');
        sessionStorage.removeItem('role');
    },

    getAuth() {
        return sessionStorage.getItem('isAuthenticated');
    },

    getUserRole() {
        return sessionStorage.getItem('role');
    },

    getUserId() {
        return sessionStorage.getItem('userId');
    },

    getUserFirstName() {
        return sessionStorage.getItem('firstName');
    },

    getUserLastName() {
        return sessionStorage.getItem('lastName');
    },

    getUserEmail() {
        return sessionStorage.getItem('email');
    }
};
export default Auth;