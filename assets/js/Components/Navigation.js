import React from 'react';

import { NavLink } from 'react-router-dom';
import Logo from '../../images/quizbank.jpg';

const Navigation = (props) => {
    return (
        <div>
            <nav className="navbar navbar-expand-lg navbar-dark header row p-0 pt-1 pb-1">
                <div className={"col-1 pr-0"}>
                    <img src={Logo} alt={"QuizBank"} className={"quizbank-logo"} />
                </div>
                <div className={"col-11"}>
                    {props.isAuth ? (
                        <section>
                            {props.role === 'ADMIN' ? (
                                <NavLink className={"navbar-brand"} to="/question">Question Dashboard</NavLink>
                            ) : (props.role === 'MASTER' ? (
                                <NavLink className={"navbar-brand"} to="/dashboard">Dashboard</NavLink>
                            ) : null)}
                            <NavLink className={"navbar-brand"} to="/logout">Logout</NavLink>
                        </section>
                    ) : (
                        <section>
                            <NavLink className={"navbar-brand"} to={"/"}>Play Quiz</NavLink>
                            <NavLink className={"navbar-brand"} to="/login">Login</NavLink>
                        </section>
                    )}
                </div>
            </nav>
        </div>
    );
}

export default Navigation;