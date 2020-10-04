import React, {Component} from 'react';
import axios from 'axios';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import AppBar from 'material-ui/AppBar';
import RaisedButton from 'material-ui/RaisedButton';
import TextField from 'material-ui/TextField';
import FlashMessage from "react-flash-message";
import {Table} from "react-bootstrap";

const apiBaseUrl = "http://quizbank.com/api/";

class QuestionCategory extends Component {
    constructor(props) {
        super(props);
        this.state = {
            categoryName: '',
            message: '',
            showAlert: false,
            alertClass: '',
            availableCategories: [],
        }

        this.header = [
            {title: "ID", prop: "id", sortable: true, filterable: true},
            {title: "Categories", prop: "category", sortable: true, filterable: true},
        ]

        this.handleButtonClick = this.handleButtonClick.bind(this);
    }

    componentDidMount() {
        axios.get(apiBaseUrl+'getQuestionCategories')
            .then((response) => {
                if (response.status === 200) {
                    let questionCategories = [];
                    response.data.questionCategories.map(value => {
                        questionCategories.push(value);
                    });
                    this.setState({
                        availableCategories: questionCategories
                    })
                }
            });
    }

    handleButtonClick() {
        const self = this;
        let availableCategories = this.state.availableCategories;
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
                            page  : [<QuestionCategory key={'category'} parentContext={self.props.parentContext} appContext={self.props.appContext} />],
                        }
                    );
                    availableCategories.push(response.data.questionCategory);
                    self.setState({
                        showAlert: true,
                        alertClass: 'alert alert-success',
                        message: successMessage,
                        availableCategories: availableCategories,
                        categoryName: ''
                    });
                } else {
                    self.setState({
                        showAlert: true,
                        alertClass: 'alert alert-error',
                        message: "Error in adding question type"
                    });
                }
            })
            .catch(function (error) {
                self.setState({
                    showAlert: true,
                    alertClass: 'alert alert-error',
                    message: "Error in adding question type"
                });
            });
    }

    render() {
        return (
            <div className={"col-12 row"}>
                <section className="col-6 border-right">
                    <div className="category-list">
                        <Table striped bordered hover>
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Category</th>
                            </tr>
                            </thead>
                            <tbody>
                            {this.state.availableCategories.map((categoryObj, index) => {
                                return (
                                    <tr className={"row-even-odd"} key={index}>
                                        <td>{categoryObj.id}</td>
                                        <td>{categoryObj.category}</td>
                                    </tr>
                                )
                            })}
                            </tbody>
                        </Table>
                    </div>
                </section>
                <section className="col-6">
                    {this.state.message ? (
                        <FlashMessage duration={5000}>
                            <div className={this.state.alertClass} role="alert">{this.state.message}</div>
                        </FlashMessage>
                    ) : null}
                    <MuiThemeProvider>
                        <div>
                            <AppBar title="Add Question Category" showMenuIconButton={false} />
                            <TextField
                                hintText="Enter question category name"
                                value={this.state.categoryName}
                                floatingLabelText="Category name"
                                onChange = {(event,newValue) => this.setState({categoryName:newValue})}
                            />
                            <br/>
                            <div className="button-div">
                                <RaisedButton className="button" label="Submit" primary={true} onClick={this.handleButtonClick}/>
                            </div>
                        </div>
                    </MuiThemeProvider>
                </section>
            </div>
        );
    }
}
export default QuestionCategory;