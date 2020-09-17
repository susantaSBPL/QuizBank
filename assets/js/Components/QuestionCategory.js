import React, {Component} from 'react';
import axios from 'axios';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import AppBar from 'material-ui/AppBar';
import RaisedButton from 'material-ui/RaisedButton';
import TextField from 'material-ui/TextField';

const apiBaseUrl = "http://quizbank.com/api/";

class QuestionCategory extends Component {
    constructor(props) {
        super(props);
        this.state = {
            categoryName: ''
        }

        this.handleButtonClick = this.handleButtonClick.bind(this);
    }

    handleButtonClick() {
        const self = this;
        const payload = {
            "category":this.state.categoryName
        }

        axios.post(apiBaseUrl+'addQuestionCategory', payload)
            .then(function (response) {
                console.log(response);
                if (response.status === 200) {
                    const successMessage = "Question Category added successfully!";
                    self.props.parentContext.setState (
                        {
                            page  : [],
                            title : '',
                            message : successMessage
                        }
                    );
                } else {
                    console.log("Error in adding question category");
                    alert("Error in adding question category");
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    }

    render() {
        return (
            <div>
                <MuiThemeProvider>
                    <div>
                        <AppBar title="Question Category" showMenuIconButton={false} />
                        <TextField
                            hintText="Enter question category name"
                            floatingLabelText="Category name"
                            onChange = {(event,newValue) => this.setState({categoryName:newValue})}
                        />
                        <br/>
                        <div className="button-div">
                            <RaisedButton className="button" label="Submit" primary={true} onClick={this.handleButtonClick}/>
                        </div>
                    </div>
                </MuiThemeProvider>
            </div>
        );
    }
}
export default QuestionCategory;