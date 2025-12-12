package pt.ipleiria.estg.dei.ourapppsiassist.models;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

import androidx.annotation.Nullable;

import java.util.ArrayList;

public class RequestBDHelper extends SQLiteOpenHelper {

    private static final String DB_NAME = "dbprojeto_v1";
    private static final int DB_VERSION = 1;
    private static final String TABLE_NAME = "request";
    private static final String COLUMN_ID = "id";
    private static final String COLUMN_CUSTOMER_ID = "customer_id";
    private static final String COLUMN_TITLE = "title";
    private static final String COLUMN_STATUS = "status";
    private static final String COLUMN_DESCRIPTION = "description";
    private static final String COLUMN_CREATED_AT = "created_at";
    private SQLiteDatabase db;

    public RequestBDHelper(@Nullable Context context) {
        super(context, DB_NAME, null, DB_VERSION);
        db = getWritableDatabase();
    }

    // ---------------------------------------------------------
    // Create DB schema
    // ---------------------------------------------------------
    @Override
    public void onCreate(SQLiteDatabase db) {
        String sql = "CREATE TABLE " + TABLE_NAME + "(" +
                COLUMN_ID + " INTEGER PRIMARY KEY, " +
                COLUMN_CUSTOMER_ID + " INTEGER NOT NULL, " +
                COLUMN_TITLE + " TEXT NOT NULL, " +
                COLUMN_STATUS + " TEXT NOT NULL, " +
                COLUMN_DESCRIPTION + " TEXT, " +
                COLUMN_CREATED_AT + " TEXT" +
                ");";
        db.execSQL(sql);
    }

    // ---------------------------------------------------------
    // Upgrade (simple clear-and-reset)
    // ---------------------------------------------------------
    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_NAME);
        onCreate(db);
    }

    // ---------------------------------------------------------
    // region CRUD - Local Database -- Return all requests
    // ---------------------------------------------------------
    public ArrayList<Request> getAllRequestsDB() {
        ArrayList<Request> requests = new ArrayList<>();

        SQLiteDatabase db = this.getReadableDatabase();
        Cursor cursor = db.query(TABLE_NAME,
                null, null, null, null, null, COLUMN_ID + "DESC");

        if (cursor.moveToFirst()) {
            do {
                Request auxrequest = new Request(
                        cursor.getInt(cursor.getColumnIndexOrThrow(COLUMN_ID)),
                        cursor.getInt(cursor.getColumnIndexOrThrow(COLUMN_CUSTOMER_ID)),
                        cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_TITLE)),
                        cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_STATUS)),
                        cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_DESCRIPTION)),
                        cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_CREATED_AT))
                );
                requests.add(auxrequest);
            } while (cursor.moveToNext());
        }
        cursor.close();
        db.close();

        return requests;
    }
    // ---------------------------------------------------------
    // Insert request into DB
    // ---------------------------------------------------------
    public Request addRequest(Request r) {
        SQLiteDatabase db = this.getWritableDatabase();
        ContentValues values = new ContentValues();

        values.put(COLUMN_ID, r.getId());
        values.put(COLUMN_CUSTOMER_ID, r.getCustomer_id());
        values.put(COLUMN_TITLE, r.getTitle());
        values.put(COLUMN_STATUS, r.getStatus());
        values.put(COLUMN_DESCRIPTION, r.getDescription());
        values.put(COLUMN_CREATED_AT, r.getCreated_at());

        //returns the id of the inserted book or -1 in case of error
        //returns db.insert(TABLE_NAME, null, values);
        long result = db.insert(TABLE_NAME, null, values);
        db.close();

        if (result == -1) {
            return null;
        }

        return r;
    }

    // ---------------------------------------------------------
    // Update an existing request
    // ---------------------------------------------------------
    public boolean editRequest(Request request) {
        SQLiteDatabase db = this.getWritableDatabase();

        ContentValues values = new ContentValues();
        values.put(COLUMN_CUSTOMER_ID, request.getCustomer_id());
        values.put(COLUMN_TITLE, request.getTitle());
        values.put(COLUMN_STATUS, request.getStatus());
        values.put(COLUMN_DESCRIPTION, request.getDescription());
        values.put(COLUMN_CREATED_AT, request.getCreated_at());

        int rows = db.update(TABLE_NAME, values,
                COLUMN_ID + " = ?", new String[]{String.valueOf(request.getId())});

        db.close();
        return rows > 0;
    }

    // ---------------------------------------------------------
    // Remove a single request by ID
    // ---------------------------------------------------------
    public boolean removeRequest(int id) {
        SQLiteDatabase db = this.getWritableDatabase();
        int rows = db.delete(TABLE_NAME, COLUMN_ID + "=?", new String[]{String.valueOf(id)});
        db.close();
        return rows > 0;
    }

    // ---------------------------------------------------------
    // Clear all requests (used during sync)
    // ---------------------------------------------------------
    public boolean removeAllRequestsDB() {
        SQLiteDatabase db = this.getWritableDatabase();
        int rows = db.delete(TABLE_NAME, null, null);
        db.close();
        return rows >= 0;
    }
}
