import React from 'react';

import { NavLink } from 'react-router-dom';

const Navigation = (props) => {
    return (
        <div>
            <nav className="navbar navbar-expand-lg navbar-dark header">
                <NavLink className={"navbar-brand"} to={"/"}>Quiz</NavLink>
                {props.isAuth ? (
                    <NavLink className={"navbar-brand"} to="/dashboard">Dashboard</NavLink>
                ) : (
                    <NavLink className={"navbar-brand"} to="/login">Login</NavLink>
                )}
                <NavLink className={"navbar-brand"} to="/question">Question Dashboard</NavLink>
            </nav>
        </div>
    );
}

export default Navigation;