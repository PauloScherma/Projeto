package pt.ipleiria.estg.dei.ourapppsiassist.models;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

import androidx.annotation.Nullable;

import java.util.ArrayList;

public class RequestBDHelper extends SQLiteOpenHelper {

    private static final String DB_NAME = "dbprojeto_v1.db";
    private static final int DB_VERSION = 4;
    private static final String TABLE_NAME = "requests";
    private static final String ID = "id";
    private static final String CUSTOMER_ID = "customer_id";
    private static final String TITLE = "title";
    private static final String STATUS = "status";
    private static final String DESCRIPTION = "description";
    private static final String CREATED_AT = "created_at";
    private static final String UPDATED_AT = "updated_at";

    private final SQLiteDatabase db;

    public RequestBDHelper(@Nullable Context context) {
        super(context, DB_NAME, null, DB_VERSION);
        this.db = getWritableDatabase();
    }

    @Override
    public void onCreate(SQLiteDatabase sqLiteDatabase) {
        String sql = "CREATE TABLE " + TABLE_NAME + " (" +
                ID + " INTEGER PRIMARY KEY, " +
                CUSTOMER_ID + " INTEGER NOT NULL, " +
                TITLE + " TEXT NOT NULL, " +
                STATUS + " TEXT NOT NULL, " +
                DESCRIPTION + " TEXT NOT NULL, " +
                CREATED_AT + " TEXT, " +      // pode ser null se ainda não veio do servidor
                UPDATED_AT + " TEXT" +
                ");";
        sqLiteDatabase.execSQL(sql);
    }

    @Override
    public void onUpgrade(SQLiteDatabase sqLiteDatabase, int oldVersion, int newVersion) {
        sqLiteDatabase.execSQL("DROP TABLE IF EXISTS " + TABLE_NAME);
        onCreate(sqLiteDatabase);
    }

    // ---------------------------------------------------------
    // INSERT
    // ---------------------------------------------------------
    public Request addRequest(Request r) {
        ContentValues values = new ContentValues();
        values.put(ID, r.getId());
        values.put(CUSTOMER_ID, r.getCustomer_id());
        values.put(TITLE, r.getTitle());
        values.put(STATUS, r.getStatus());
        values.put(DESCRIPTION, r.getDescription());
        values.put(CREATED_AT, r.getCreated_at());
        values.put(UPDATED_AT, r.getUpdated_at());

        long id = this.db.insert(TABLE_NAME, null, values);
        if (id > -1) return r;
        return null;
    }

    // ---------------------------------------------------------
    // UPDATE
    // ---------------------------------------------------------
    public boolean editRequest(Request r) {
        ContentValues values = new ContentValues();
        values.put(CUSTOMER_ID, r.getCustomer_id());
        values.put(TITLE, r.getTitle());
        values.put(STATUS, r.getStatus());
        values.put(DESCRIPTION, r.getDescription());
        values.put(CREATED_AT, r.getCreated_at());
        values.put(UPDATED_AT, r.getUpdated_at());

        int numLinhas = this.db.update(TABLE_NAME, values, ID + " = ?", new String[]{r.getId() + ""});
        return numLinhas > 0;
    }

    // ---------------------------------------------------------
    // DELETE
    // ---------------------------------------------------------
    public boolean removeRequest(int id) {
        int numLinhas = this.db.delete(TABLE_NAME, ID + " = ?", new String[]{id + ""});
        return numLinhas > 0;
    }

    // ---------------------------------------------------------
    // CLEAR TABLE
    // ---------------------------------------------------------
    public void removeAllRequestsDB() {
        this.db.delete(TABLE_NAME, null, null);
    }

    // ---------------------------------------------------------
    // SELECT ALL
    // ---------------------------------------------------------
    public ArrayList<Request> getAllRequestsDB() {
        ArrayList<Request> requests = new ArrayList<>();

        Cursor cursor = this.db.query(
                TABLE_NAME,
                new String[]{ID, CUSTOMER_ID, TITLE, STATUS, DESCRIPTION, CREATED_AT, UPDATED_AT},
                null, null, null, null, null
        );

        if (cursor.moveToFirst()) {
            do {
                Request r = new Request(
                        cursor.getInt(0),      // id
                        cursor.getInt(1),      // customer_id
                        cursor.getString(2),   // title
                        cursor.getString(3),   // status
                        cursor.getString(4),   // description
                        cursor.getString(5),   // created_at
                        cursor.getString(6)    // updated_at
                );
                requests.add(r);
            } while (cursor.moveToNext());
        }

        cursor.close();
        return requests;
    }
}
