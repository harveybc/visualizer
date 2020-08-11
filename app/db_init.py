from flask import Blueprint
from flask import Flask
from flask_sqlalchemy import SQLAlchemy
import click

app = Flask(__name__)
db = SQLAlchemy()

bp_init_db = Blueprint('init_db', __name__)

@bp_init_db.cli.command('init_db')
@click.option(
    "--safety/--no-safety",
    default=True,
    help="Confirm DB_URI before reset?",
)
def init_db():
    """
    Initialize the database.

    :param safety: confirmation of DB_URI to prevent screw up in prod
    :type safety: bool default True
    :return: None
    """
    begin_reset = False
    if not safety:
        begin_reset = True
    else:
        response = input("Warning: It will delete any existing data in the db. Do you wish to continue? [Y/N]")
        if response.upper() == "Y":
            begin_reset = True
        else:
            raise SystemExit()
    if begin_reset:
        print("Dropping database")
        db.drop_all()
        print("Creating database")
        from models.user import User
        db.create_all()
        print("Seeding database")
        from models.user_seed import user_seed
        user_seed(app, db)
        click.echo("All tables dropped and recreated")


# you MUST register the blueprint
app.register_blueprint(bp_init_db)


    