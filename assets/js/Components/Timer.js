import React, {Component} from 'react';

class Timer extends Component {
    constructor(props) {
        super(props);
        this.state = {
            seconds: props.time > 60 ? props.time%60 : props.time,
            minutes: parseInt((props.time)/60)
        }
    }

    componentDidMount() {
        this.startTimer();
    }

    startTimer() {
        this.myInterval = setInterval(() => {
            const { seconds, minutes } = this.state;
            if (seconds > 0) {
                this.setState(({ seconds }) => ({
                    seconds: seconds - 1
                }))
            }
            if (seconds === 0) {
                if (minutes === 0) {
                    clearInterval(this.myInterval)
                } else {
                    this.setState(({ minutes }) => ({
                        minutes: minutes - 1,
                        seconds: 59
                    }))
                }
            }
        }, 1000)
    }

    componentWillUnmount() {
        clearInterval(this.myInterval)
    }

    render() {
        const { minutes, seconds } = this.state
        return (
            <div className={"score float-right"}>
                <h5>{minutes}:{seconds < 10 ? `0${seconds}` : seconds}</h5>
            </div>
        )
    }
}
export default Timer;